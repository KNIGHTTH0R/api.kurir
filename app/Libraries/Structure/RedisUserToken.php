<?php
namespace app\Libraries\Structure;

class RedisUserToken extends StructureAbstract
{
    protected $attributes = ['access_token'];

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
        $this->setAttribute('access_token', $this->redisAccessToken->getAccessToken());
    }
}