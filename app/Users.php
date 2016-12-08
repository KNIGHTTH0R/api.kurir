<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{

    const TYPE_ADMIN = 'admin';
    const TYPE_CUSTOMER = 'customer';
    const TYPE_KURIR = 'kurir';

    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $connection = 'mysql';

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function CustomerItems()
    {
        return $this->hasMany('App\Items', 'id_customer', 'id');
    }

    public function KurirItems()
    {
        return $this->hasMany('App\Items', 'id_kurir', 'id');
    }
}
