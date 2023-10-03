<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wholesaler extends Model
{
    protected $guarded=['id', 'wholesaler_id'];
    protected $primaryKey = 'wholesaler_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = ['id'];

    public function getRouteKeyName()
    {
        return 'wholesaler_id';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
