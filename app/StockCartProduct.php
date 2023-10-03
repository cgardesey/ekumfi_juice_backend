<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockCartProduct extends Model
{
    protected $guarded = ['id', 'stock_cart_product_id'];
    protected $primaryKey = 'stock_cart_product_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function getRouteKeyName()
    {
        return 'stock_cart_product_id';
    }
}
