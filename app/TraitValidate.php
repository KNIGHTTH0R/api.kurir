<?php

namespace App;

use Illuminate\Support\Facades\Validator;

trait TraitValidate
{
    /**
     * @var Validator
     */
    private $validator;

    private function runValidation($request, $rules){
        $this->validator = Validator::make($request->all(), $rules);
        if($this->validator->fails()){
            return false;
        }

        return true;
    }
}
