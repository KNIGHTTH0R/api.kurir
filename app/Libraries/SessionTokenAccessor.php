<?php

namespace app\Libraries;


use App\AuthToken;
use app\Libraries\Structure\RedisAccessToken;
use app\Libraries\Structure\SessionToken;

class SessionTokenAccessor{

    private static $instance;

    /**
     * @var AuthToken
     */
    private $authToken;

    /**
     * @var SessionToken $sessionToken
     */
    private $sessionToken;

    /**
     * @var RedisAccessToken $redisAccessToken
     */
    private $redisAccessToken;

    private $redisData;

    public static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function __construct(AuthToken $authToken = null)
    {
        $this->authToken = is_null($authToken) ? AuthToken::getInstance() : $authToken;
        $this->redisAccessToken =  $this->authToken->getRedisAccessToken();
        $this->sessionToken = $this->authToken->getSessionToken();

        $this->setRedisData();
    }

    public function setRedisData(){
        $this->redisData = $this->redisAccessToken->getAttribute();
    }

    public function getRedisData(){
        return $this->redisData;
    }

    public function getSessionUserID(){
        return $this->sessionToken->getUserId();
    }

    public function getSessionUserType(){
        return $this->sessionToken->getUserType();
    }
}