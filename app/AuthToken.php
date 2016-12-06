<?php

namespace App;

use app\Libraries\Structure\RedisAccessToken;
use app\Libraries\Structure\RedisRefreshToken;
use app\Libraries\Structure\RedisUserToken;
use app\Libraries\Structure\SessionToken;
use Illuminate\Support\Facades\Cache;

class AuthToken
{
    CONST TOKEN_PREFIX = 'token';

    CONST ACCESS_TOKEN_CACHE_TIME = 60; // in minutes

    CONST REFRESH_TOKEN_CACHE_TIME = 300; // in minutes

    CONST USER_TOKEN_CACHE_TIME = 60; // in minutes

    private $access_token, $refresh_token, $user_token;

    /**
     * @var SessionToken
     */
    private $sessionToken;

    /**
     * @var RedisAccessToken
     */
    private $redisAccessToken;

    /**
     * @var RedisUserToken
     */
    private $redisUserToken;

    /**
     * @var RedisRefreshToken
     */
    private $redisRefreshToken;


    /**
     * @var self
     */
    private static $instance;

    public static function getInstance(){
        if(is_null(self::$instance)){
            throw new \Exception(trans('there is no instance created'));
        }

        return self::$instance;
    }

    public static function getFreshInstance($user_id, $hashed_password){
        if(is_null(self::$instance)){
            self::$instance = new self;
            self::$instance->generateNewSessionToken($user_id, $hashed_password);
        }

        return self::$instance;
    }

    public static function getInstanceFromRefreshToken($refresh_token){
        if(is_null(self::$instance)){
            self::$instance = new self;
            self::$instance->setSessionTokenByRefreshToken($refresh_token);
        }

        return self::$instance;
    }

    public static function getInstanceFromAccessToken($access_token){
        if(is_null(self::$instance)){
            self::$instance = new self;
            self::$instance->setSessionTokenByAccessToken($access_token);
        }

        return self::$instance;
    }


    /**
     * @param SessionToken $sessionToken
     * @return $this
     */
    protected function setSessionToken(SessionToken $sessionToken){
        $this->sessionToken = $sessionToken;
        return $this;
    }

    /**
     * @return SessionToken
     */
    public function getSessionToken(){
        return $this->sessionToken;
    }

    /**
     * @param RedisAccessToken $redisAccessToken
     * @return $this
     */
    protected function setRedisAccessToken(RedisAccessToken $redisAccessToken){
        $this->redisAccessToken = $redisAccessToken;
        return $this;
    }

    /**
     * @return RedisAccessToken
     */
    public function getRedisAccessToken(){
        return $this->redisAccessToken;
    }

    /**
     * @param RedisUserToken $userToken
     * @return $this
     */
    protected function setRedisUserToken(RedisUserToken $userToken){
        $this->redisUserToken = $userToken;
        return $this;
    }

    /**
     * @return RedisUserToken
     */
    public function getRedisUserToken(){
        return $this->redisUserToken;
    }

    /**
     * @param RedisRefreshToken $userToken
     * @return $this
     */
    protected function setRedisRefreshToken(RedisRefreshToken $userToken){
        $this->redisRefreshToken = $userToken;
        return $this;
    }

    /**
     * @return RedisRefreshToken
     */
    public function getRedisRefreshToken(){
        return $this->redisRefreshToken;
    }

    /**
     * ACCESS TOKEN
     * @param $access_token
     * @return $this
     */
    protected function setAccessToken($access_token){
        $this->access_token = $access_token;
        return $this;
    }

    protected function setNewAccessToken(){
        $uniqId = $this->generateUniqId();
        $this->access_token = sprintf('%s%s', md5(sprintf('accesstoken:%s:%s', self::TOKEN_PREFIX, $uniqId)), $uniqId);
        return $this;
    }

    public function getAccessToken(){
        return $this->access_token;
    }

    /**
     * USER TOKEN
     * @param $user_id
     * @return $this
     */
    protected function setUserToken($user_id){
        $this->user_token = sprintf('user:accesstoken:reference:%d', $user_id);
        return $this;
    }

    public function getUserToken(){
        return $this->user_token;
    }

    /**
     * REFRESH TOKEN
     * @param $refresh_token
     * @return $this
     */
    protected function setRefreshToken($refresh_token){
        $this->refresh_token = $refresh_token;
        return $this;
    }

    protected function setNewRefreshToken(){
        $uniqId = $this->generateUniqId();
        $this->refresh_token = sprintf('%s%s', md5(sprintf('refreshtoken:%s:%s', self::TOKEN_PREFIX, $uniqId)), $uniqId);
        return $this;
    }

    public function getRefreshToken(){
        return $this->refresh_token;
    }

    protected function isTokenFoundInCache($access_token){
        return Cache::get($access_token);
    }

    protected function generateUniqId(){
        return microtime(true) * 10000;
    }

    protected function getCurrentDate(){
        return date('Y-m-d H:i:s');
    }

    protected function isExistUserInDB($user_id){
        $user = Users::find($user_id);
        if(!$user){
            throw new \Exception(sprintf("User Id %d is not exist in database", $user_id));
        }

        return $user;
    }

    protected function isExistUserInRedisUserToken($user_id){
        $this->setUserToken($user_id);
        if($data = Cache::get($this->getUserToken())){
            return $sessionToken = $this->setSessionTokenByAccessToken($data['access_token']);
        }

        return null;
    }

    protected function initialize(RedisAccessToken $redisAccessToken){
        $this->setAccessToken($redisAccessToken->getAccessToken())
            ->setRefreshToken($redisAccessToken->getRefreshToken())
            ->setRedisAccessToken($redisAccessToken)
            ->setRedisUserToken(new RedisUserToken($redisAccessToken))
            ->setRedisRefreshToken(new RedisRefreshToken($redisAccessToken))
            ->setSessionToken(new SessionToken($redisAccessToken));
    }

    protected function setSessionTokenByAccessToken($access_token){
        if($data = $this->isTokenFoundInCache($access_token)){
            $data['access_token'] = $access_token;
            $this->initialize(new RedisAccessToken($data));
            return $this->sessionToken;
        }

        return null;
    }

    protected function setSessionTokenByRefreshToken($resfresh_token){
        if($data = $this->isTokenFoundInCache($resfresh_token)){
            return $this->setNewToken($data['user_id']);
        }

        return null;
    }

    private function setNewToken($user_id, $hashed_password){
        // 1. check in database
        $user = $this->isExistUserInDB($user_id);

        // 2. set new token
        $this->setNewAccessToken()->setNewRefreshToken()->setUserToken($user_id);

        // 3. generate new access token
        $this->initialize(new RedisAccessToken([
            'access_token' => $this->getAccessToken(),
            'refresh_token' => $this->getRefreshToken(),
            'user_id' => $user_id,
            'hashed_password' => $hashed_password,
            'user_type' => $user->type,
            'token_type' => RedisAccessToken::TOKEN_TYPE,
            'created_at' => $this->getCurrentDate(),
        ]));

        Cache::put($this->getUserToken(), $this->redisUserToken->getAttribute(), self::USER_TOKEN_CACHE_TIME);
        Cache::put($this->getAccessToken(), $this->redisAccessToken->getAttribute(), self::ACCESS_TOKEN_CACHE_TIME);
        Cache::put($this->getRefreshToken(), $this->redisRefreshToken->getAttribute(), self::REFRESH_TOKEN_CACHE_TIME);

        return $this->sessionToken;
    }

    protected function generateNewSessionToken($user_id, $hashed_password){
        // 1. check in cache by user_id
        if($sessionToken = $this->isExistUserInRedisUserToken($user_id)){
            return $sessionToken;
        }

        // 2. set new token
        return $this->setNewToken($user_id, $hashed_password);
    }
}
