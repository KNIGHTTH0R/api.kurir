<?php
namespace app\Libraries\Structure;

abstract class StructureAbstract
{
    CONST TOKEN_TYPE = 'bearer';

    protected $attributes = [];

    protected $data;

    public function __construct($data)
    {
        $this->initialize($data);
    }

    protected function initialize($data){
        if(!is_array($data) || empty($data)){
            throw new \Exception(sprintf('Parameter data must be an array and cannot be empty : %s', var_export($data, true)));
        }

        foreach($this->attributes as $attribute_name){
            if(!array_key_exists($attribute_name, $data)){
                throw new \Exception(sprintf('Parameter data must be contain index : "%s"', $attribute_name));
            }

            $this->setAttribute($attribute_name, $data[$attribute_name]);
        }

        return true;
    }

    public function setAttribute($attribute_name, $attribute_value){
        if(!in_array($attribute_name, $this->attributes)){
            throw new \Exception(sprintf('attribute %s does not exist', $attribute_name));
        }

        $method = 'set_'. $attribute_name;
        if (method_exists($this, $method))
        {
            $this->$method($attribute_value);
        }
        else
        {
            $this->data[$attribute_name] = $attribute_value;
        }

        return $this;
    }

    public function getAttribute($attribute_name = null){
        if(is_null($attribute_name)){
            return $this->data;
        }

        return !in_array($attribute_name, $this->attributes) ? null : $this->data[$attribute_name];
    }
}