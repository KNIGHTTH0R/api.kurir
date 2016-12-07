<?php
namespace App\Transformer;;

class Users extends \League\Fractal\TransformerAbstract
{
    public function transform(\App\Users $users)
    {
        return [
            'id' => $users->id,
            'name' => $users->name,
            'email' => $users->email,
            'phone_number' => $users->phone_number,
            'password' => $users->password,
            'type' => $users->type,
            'created_at' => $users->created_at,
            'updated_at' => $users->updated_at,
        ];
    }
}