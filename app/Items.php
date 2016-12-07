<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    protected $table = 'items';

    protected $primaryKey = 'id';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $connection = 'mysql';

    public function scopeOfStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOfCustomer($query, $idCustomer)
    {
        return $query->where('id_customer', $idCustomer);
    }

    public function scopeOfKurir($query, $idKurir)
    {
        return $query->where('id_kurir', $idKurir);
    }
}
