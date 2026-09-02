<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayoutAccount extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'network_id', 'country_code', 'method',
        'account_identifier', 'account_name', 'verified_at', 'is_active',
        'mpesa_environment', 'mpesa_consumer_key', 'mpesa_consumer_secret',
        'mpesa_passkey', 'mpesa_shortcode',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'is_active'   => 'boolean',
    ];

    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class);
    }
}
