<?php

namespace App\Http\Middleware;

use App\TraitPrivilegeMiddleware;

class KurirPrivilegeMiddleware
{

    use TraitPrivilegeMiddleware;

    CONST USER_TYPE_ALLOWED = 'kurir';

    private function checkAccessByUserType(){
        return $this->sessionToken->getAttribute('user_type') === self::USER_TYPE_ALLOWED;
    }
}
