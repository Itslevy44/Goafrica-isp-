<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'country', 'default_currency', 'status',
        'subscription_ends_at',
        'mpesa_environment',
        'mpesa_consumer_key',
        'mpesa_consumer_secret',
        'mpesa_passkey',
        'mpesa_shortcode'
    ];

    protected $casts = [
        'subscription_ends_at' => 'datetime',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function networks(): HasMany
    {
        return $this->hasMany(Network::class);
    }
}
