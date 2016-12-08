<?php
namespace App\Transformer;

class Users extends \League\Fractal\TransformerAbstract
{
    public function transform(\App\Users $users)
    {
        return [
            'id' => $users->id,
            'name' => $users->name,
            'email' => $users->email,
            'phone_number' => $users->phone_number,
            'type' => $users->type,
            'timestamp' => [
                'created' => $users->created_at, //$user->created_at->toDateTimeString()
                'updated' => $users->updated_at, //$user->updated_at->toDateTimeString()
            ]
        ];
    }
}