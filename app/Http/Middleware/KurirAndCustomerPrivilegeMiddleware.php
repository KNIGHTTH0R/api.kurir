<?php

namespace App\Http\Middleware;

use App\TraitPrivilegeMiddleware;

class KurirAndCustomerPrivilegeMiddleware
{

    use TraitPrivilegeMiddleware;

    private function checkAccessByUserType(){
        return in_array($this->sessionToken->getAttribute('user_type'), [
            KurirPrivilegeMiddleware::USER_TYPE_ALLOWED,
            CustomerPrivilegeMiddleware::USER_TYPE_ALLOWED
        ]);
    }
}
