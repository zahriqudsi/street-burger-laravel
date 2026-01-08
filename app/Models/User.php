<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'phone_number',
        'password',
        'name',
        'email',
        'email_verified',
        'date_of_birth',
        'role',
        'push_token',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified' => 'boolean',
        'date_of_birth' => 'date',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role,
        ];
    }
}
