<?php

namespace App\Http\Middleware;

use App\TraitPrivilegeMiddleware;

class LoggedPrivilegeMiddleware
{

    use TraitPrivilegeMiddleware;

    private function checkAccessByUserType(){
        return in_array($this->sessionToken->getAttribute('user_type'), [
            KurirPrivilegeMiddleware::USER_TYPE_ALLOWED,
            AdminPrivilegeMiddleware::USER_TYPE_ALLOWED
        ]);
    }
}
