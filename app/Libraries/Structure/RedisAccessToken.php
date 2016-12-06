<?php
namespace app\Libraries\Structure;

class RedisAccessToken extends StructureAbstract
{
    protected $attributes = ['user_id', 'hashed_password', 'user_type', 'token_type', 'refresh_token', 'created_at'];

    private $access_token;

    protected function initialize($data)
    {
        if(!is_array($data) || empty($data)){
            throw new \Exception(sprintf('Parameter data must be an array and cannot be empty : %s', var_export($data, true)));
        }

        if(!isset($data['access_token'])){
            throw new \Exception('Access token parameter required');
        }

        $this->access_token = $data['access_token'];

        foreach($this->attributes as $attribute_name){
            if(!array_key_exists($attribute_name, $data)){
                throw new \Exception(sprintf('Parameter data must be contain index : "%s"', $attribute_name));
            }

            $this->setAttribute($attribute_name, $data[$attribute_name]);
        }

        return true;
    }

    public function getCurrentTimeStamp(){
        return strtotime(date('Y-m-d H:i:s'));
    }

    public function getAccessToken(){
        return $this->access_token;
    }

    public function getRefreshToken(){
        return $this->getAttribute('refresh_token');
    }
}