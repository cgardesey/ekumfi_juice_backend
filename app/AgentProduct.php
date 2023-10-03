<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentProduct extends Model
{
    protected $guarded = ['id', 'agent_product_id'];
    protected $primaryKey = 'agent_product_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = ['id'];

    public function getRouteKeyName()
    {
        return 'agent_product_id';
    }
}
