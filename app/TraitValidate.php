<?php

namespace App;

use Illuminate\Support\Facades\Validator;

trait TraitValidate
{
    /**
     * @var Validator
     */
    private $validator;

    private function runValidation($inputs, $rules){
        $this->validator = Validator::make($inputs, $rules);
        if($this->validator->fails()){
            return false;
        }

        return true;
    }
}
