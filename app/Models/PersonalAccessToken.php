<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumToken;
class PersonalAccessToken extends SanctumToken
{
    protected $fillable = [
        'name',
        'token',
        'abilities',
        'device_id',
        'ip_address',
        'user_agent',
        'browser',
        'platform',
        'device',
        'last_used_at',
        'access_expires_at',
        'refresh_expires_at',
        'token_type',
    ];

    protected $casts = [
        'abilities' => 'json',
        'last_used_at' => 'datetime',
        'access_expires_at' => 'datetime',
        'refresh_expires_at' => 'datetime',
    ];
}
