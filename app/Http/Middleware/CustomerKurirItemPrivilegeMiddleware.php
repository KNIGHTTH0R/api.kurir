<?php

namespace App\Http\Middleware;

use App\TraitPrivilegeMiddleware;
use Illuminate\Http\Request;
use App\Items as ItemsModel;

class CustomerKurirItemPrivilegeMiddleware
{

    use TraitPrivilegeMiddleware;

    private function checkAccessByUserType(Request $request)
    {
        $userTypeSession = $this->sessionToken->getAttribute('user_type');

        if (!in_array($userTypeSession, [CustomerPrivilegeMiddleware::USER_TYPE_ALLOWED, KurirPrivilegeMiddleware::USER_TYPE_ALLOWED])) {
            return false;
        }

        $id = $request->segment(3);

        /** @var ItemsModel $item */
        $item = ItemsModel::find(['id' => $id])->first();

        if ($userTypeSession === CustomerPrivilegeMiddleware::USER_TYPE_ALLOWED && $item->id_customer !== $this->sessionToken->getAttribute('user_id')) {
            return false;
        }

        if ($userTypeSession === KurirPrivilegeMiddleware::USER_TYPE_ALLOWED && $item->id_kurir !== $this->sessionToken->getAttribute('user_id')) {
            return false;
        }

        return true;
    }
}
