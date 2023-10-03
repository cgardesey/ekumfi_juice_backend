<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class wholesalerProduct extends Model
{
    protected $guarded = ['id', 'wholesaler_product_id'];
    protected $primaryKey = 'wholesaler_product_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = ['id'];

    public function getRouteKeyName()
    {
        return 'wholesaler_product_id';
    }
}
