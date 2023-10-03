<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = ['id', 'payment_id'];
    protected $primaryKey = 'payment_id';
    public $incrementing = false;
    protected $keyType = 'string';
}
