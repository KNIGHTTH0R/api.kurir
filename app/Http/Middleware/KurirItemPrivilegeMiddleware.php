<?php

namespace App\Http\Middleware;

use App\TraitPrivilegeMiddleware;
use Illuminate\Http\Request;
use App\Items as ItemsModel;

class KurirItemPrivilegeMiddleware
{

    use TraitPrivilegeMiddleware;

    CONST USER_TYPE_ALLOWED = 'kurir';

    private function checkAccessByUserType(Request $request)
    {
        if ($this->sessionToken->getAttribute('user_type') !== self::USER_TYPE_ALLOWED) {
            return false;
        }

        $id = $request->segment(3);

        /** @var ItemsModel $item */
        $item = ItemsModel::find(['id' => $id])->first();

        if ($item->id_kurir !== $this->sessionToken->getAttribute('user_id')) {
            return false;
        }

        return true;
    }
}
