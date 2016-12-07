<?php
namespace App\Transformer;;

class Items extends \League\Fractal\TransformerAbstract
{
    public function transform(\App\Items $items)
    {
        return [
            'id' => $items->id,
            'status' => $items->status,
            'item_name' => $items->name,
            'receiver' => [
                'name' => $items->receiver_name,
                'phone_number' => $items->receiver_phone_number,
                'address' => $items->destination_address,
            ],
            'sender' => [
                'id' => $items->id_customer,
                'address' => $items->pickup_address,
            ],
            'kurir' => [
                'id' => $items->id_kurir,
            ],
            'timestamp' => [
                'created' => $items->created_at, //$user->created_at->toDateTimeString()
                'updated' => $items->updated_at, //$user->updated_at->toDateTimeString()
            ]
        ];
    }
}