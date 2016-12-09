<?php

namespace App\Http\Middleware;

use App\TraitPrivilegeMiddleware;
use Illuminate\Http\Request;

class LoggedPrivilegeMiddleware
{

    use TraitPrivilegeMiddleware;

    private function checkAccessByUserType(Request $request)
    {
        return in_array($this->sessionToken->getAttribute('user_type'), [
            KurirPrivilegeMiddleware::USER_TYPE_ALLOWED,
            AdminPrivilegeMiddleware::USER_TYPE_ALLOWED,
            CustomerPrivilegeMiddleware::USER_TYPE_ALLOWED
        ]);
    }
}
