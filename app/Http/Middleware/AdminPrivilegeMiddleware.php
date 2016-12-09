<?php

namespace App\Http\Middleware;

use App\TraitPrivilegeMiddleware;
use Illuminate\Http\Request;

class AdminPrivilegeMiddleware
{

    use TraitPrivilegeMiddleware;

    CONST USER_TYPE_ALLOWED = 'admin';

    private function checkAccessByUserType(Request $request)
    {
        return $this->sessionToken->getAttribute('user_type') === self::USER_TYPE_ALLOWED;
    }
}
