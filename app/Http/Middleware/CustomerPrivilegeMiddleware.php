<?php

namespace App\Http\Middleware;

use App\TraitPrivilegeMiddleware;

class CustomerPrivilegeMiddleware
{

    use TraitPrivilegeMiddleware;

    CONST USER_TYPE_ALLOWED = 'customer';

    private function checkAccessByUserType(){
        return $this->sessionToken->getAttribute('user_type') === self::USER_TYPE_ALLOWED;
    }
}
