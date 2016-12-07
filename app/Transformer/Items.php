<?php
namespace App\Transformer;;

class Items extends \League\Fractal\TransformerAbstract
{
    public function transform(\App\Items $items)
    {
        return array_filter([
            'id' => $items->id,
            'status' => $items->status,
            'item_name' => $items->name,
            'receiver' => [
                'name' => $items->receiver_name,
                'phone_number' => $items->receiver_phone_number,
            ],
            'sender' => [
                'id' => $items->id_customer,
                'link' => route('users.show', [$items->id_customer]),
            ],
            'address' => [
                'pickup' => $items->pickup_address,
                'destination' => $items->destination_address,
            ],
            'kurir' => array_filter([
                'id' => $items->id_kurir,
                'link' => !is_null($items->id_kurir) ? route('users.show', [$items->id_kurir]) : null
            ]),
            'timestamp' => array_filter([
                'created' => $items->created_at, //$user->created_at->toDateTimeString()
                'updated' => $items->updated_at, //$user->updated_at->toDateTimeString()
            ])
        ]);
    }
}