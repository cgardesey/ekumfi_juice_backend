<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /*protected $fillable = [
        'email', 'password', 'role',
    ];*/

    protected $guarded = ['id', 'user_id'];
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'active', 'otp', 'apphash', 'osversion', 'sdkversion', 'device', 'devicemodel', 'deviceproduct', 'manufacturer', 'androidid', 'versionrelease', 'deviceheight', 'devicewidth', 'email_verified_at', 'password', 'api_token', 'email_verified'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
