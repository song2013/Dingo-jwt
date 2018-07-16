<?php

namespace App\Models\Api;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class ApiUser extends Authenticatable implements JWTSubject
{
    //
    use Notifiable;
    protected $table="api_users";

    protected $fillable = [
        'name',  'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
