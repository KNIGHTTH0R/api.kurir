<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    const STATUS_NEW = 'new';
    const STATUS_PROGRESS = 'on_progress';
    const STATUS_SENT = 'sent';

    protected $table = 'items';

    protected $primaryKey = 'id';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $connection = 'mysql';

    public function Kurir()
    {
        return $this->belongsTo('App\Users', 'id_kurir', 'id');
    }

    public function Customer()
    {
        return $this->belongsTo('App\Users', 'id_customer', 'id');
    }

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
