<?php
namespace app\Libraries\Structure;

class RedisRefreshToken extends StructureAbstract
{
    protected $attributes = ['user_id'];

    /**
     * @var RedisAccessToken
     */
    private $redisAccessToken;

    public function __construct(RedisAccessToken $redisAccessToken)
    {
        $this->redisAccessToken = $redisAccessToken;
        $this->redisAccessToken->getAttribute();
        $this->generateUserToken();
    }

    public function generateUserToken(){
        $this->setAttribute('user_id', $this->redisAccessToken->getAttribute('user_id'));
    }
}