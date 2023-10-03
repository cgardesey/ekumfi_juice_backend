<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class wholesalerCart extends Model
{
    protected $guarded = ['id', 'wholesaler_cart_id'];
    protected $primaryKey = 'wholesaler_cart_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function getRouteKeyName()
    {
        return 'wholesaler_cart_id';
    }
}
