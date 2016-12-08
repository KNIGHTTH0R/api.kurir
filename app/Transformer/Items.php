<?php
namespace App\Transformer;

use App\Items as ModelItems;
use App\Users as ModelUsers;
use App\Transformer\Users as TransformerUsers;

class Items extends \League\Fractal\TransformerAbstract
{
    use TraitIncludes;

    public function transform(ModelItems $items)
    {
        $this->checkIncludes();

        return array_filter([
            'id' => $items->id,
            'status' => $items->status,
            'item_name' => $items->name,
            'receiver' => [
                'name' => $items->receiver_name,
                'phone_number' => $items->receiver_phone_number,
            ],
            'sender' => $this->getCustomer($items->Customer),
            'address' => [
                'pickup' => $items->pickup_address,
                'destination' => $items->destination_address,
            ],
            'kurir' => $this->getKurir($items->Kurir),
            'timestamp' => array_filter([
                'created' => $items->created_at, //$user->created_at->toDateTimeString()
                'updated' => $items->updated_at, //$user->updated_at->toDateTimeString()
            ])
        ]);
    }

    private function getKurir(ModelUsers $users = null)
    {
        if (is_null($users)) {
            return null;
        }

        if ($this->isEnabledInclude('kurir')) {
            return (new TransformerUsers())->transform($users);
        }

        return array_filter([
            'id' => $users->id,
            'link' => !is_null($users->id) ? route('users.show', [$users->id]) : null
        ]);
    }

    private function getCustomer(ModelUsers $users = null)
    {
        if (is_null($users)) {
            return null;
        }

        if ($this->isEnabledInclude('customer')) {
            return (new TransformerUsers())->transform($users);
        }

        return array_filter([
            'id' => $users->id,
            'link' => !is_null($users->id) ? route('users.show', [$users->id]) : null
        ]);
    }

    private function getIncludesOptions()
    {
        return ['kurir', 'customer'];
    }
}