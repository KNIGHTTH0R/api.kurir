<?php

namespace App\Http\Middleware;

use app\Libraries\Structure\SessionToken;
use App\TraitPrivilegeMiddleware;
use Illuminate\Http\Request;
use App\Items as ItemsModel;

class CustomerKurirItemPrivilegeMiddleware
{

    use TraitPrivilegeMiddleware;

    private function checkAccessByUserType(Request $request)
    {
        /** @var SessionToken $sessionToken */
        $sessionToken = $request->loggedUser;

        if (!in_array($sessionToken->getUserType(), [CustomerPrivilegeMiddleware::USER_TYPE_ALLOWED, KurirPrivilegeMiddleware::USER_TYPE_ALLOWED])) {
            return false;
        }

        $id = $request->segment(3);

        /** @var ItemsModel $item */
        $item = ItemsModel::find(['id' => $id])->first();
        $request->item = $item;

        if ($sessionToken->getUserType() === CustomerPrivilegeMiddleware::USER_TYPE_ALLOWED && $item->id_customer !== $this->sessionToken->getAttribute('user_id')) {
            return false;
        }

        if (
            $sessionToken->getUserType() === KurirPrivilegeMiddleware::USER_TYPE_ALLOWED
            && !is_null($item->id_kurir)
            && $item->id_kurir !== $this->sessionToken->getAttribute('user_id')) {
            return false;
        }

        return true;
    }
}
