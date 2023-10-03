<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EkumfiInfo extends Model
{
    protected $guarded = ['id', 'ekumfi_info_id'];
    protected $primaryKey = 'ekumfi_info_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = ['id'];

    public function getRouteKeyName()
    {
        return 'ekumfi_info_id';
    }
}
