<?php
namespace app\Libraries\Structure;

use App\AuthToken;

class SessionToken extends StructureAbstract
{
    protected $attributes = ['access_token', 'refresh_token', 'user_type', 'user_id', 'hashed_password', 'token_type', 'expires_in'];

    /**
     * @var RedisAccessToken
     */
    private $redisAccessToken;

    public function __construct(RedisAccessToken $redisAccessToken)
    {
        $this->redisAccessToken = $redisAccessToken;
        $data = $this->redisAccessToken->getAttribute();

        $this->generateSessionToken($data);
    }

    private function getExpiresIn(){
        $expiredin = strtotime($this->redisAccessToken->getAttribute('created_at')) - $this->redisAccessToken->getCurrentTimeStamp() + (AuthToken::ACCESS_TOKEN_CACHE_TIME * 60);
        return $expiredin > 0 ? $expiredin : 0;
    }

    public function set_expires_in(){
        $this->data['expires_in'] = $this->getExpiresIn();
    }

    public function generateSessionToken($data){
        $this->setAttribute('access_token', $this->redisAccessToken->getAccessToken());
        $this->setAttribute('refresh_token', $this->redisAccessToken->getRefreshToken());
        $this->setAttribute('user_id', $data['user_id']);
        $this->setAttribute('user_type', $data['user_type']);
        $this->setAttribute('hashed_password', $data['hashed_password']);
        $this->setAttribute('token_type', $data['token_type']);
        $this->set_expires_in();
    }

    public function getUserId(){
        return $this->getAttribute('user_id');
    }

    public function getAccessToken(){
        return $this->getAttribute('access_token');
    }

    public function getRefreshToken(){
        return $this->getAttribute('refresh_token');
    }

    public function getUserType(){
        return $this->getAttribute('user_type');
    }

    public function getTokenType(){
        return $this->getAttribute('token_type');
    }

    public function getAttribute($attribute_name = null){
        if(is_null($attribute_name)){
            $this->setAttribute('expires_in', $this->getExpiresIn());
            return $this->data;
        }

        if($attribute_name === 'expires_in'){
            return $this->getExpiresIn();
        }

        return !in_array($attribute_name, $this->attributes) ? null : $this->data[$attribute_name];
    }
}