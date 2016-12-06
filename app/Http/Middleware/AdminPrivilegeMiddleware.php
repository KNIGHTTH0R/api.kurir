<?php

namespace App\Http\Middleware;

use App\TraitPrivilegeMiddleware;

class AdminPrivilegeMiddleware
{

    use TraitPrivilegeMiddleware;

    CONST USER_TYPE_ALLOWED = 'admin';

    private function checkAccessByUserType(){
        return $this->sessionToken->getAttribute('user_type') === self::USER_TYPE_ALLOWED;
    }
}
