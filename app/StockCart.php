<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockCart extends Model
{
    protected $guarded = ['id', 'stock_cart_id'];
    protected $primaryKey = 'stock_cart_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function getRouteKeyName()
    {
        return 'stock_cart_id';
    }
}
