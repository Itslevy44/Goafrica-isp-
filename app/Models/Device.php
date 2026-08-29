<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Device extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'network_id', 'type', 'name', 'ip_address', 
        'api_port', 'credentials_encrypted', 'status', 'last_seen_at'
    ];

    protected $casts = [
        'credentials_encrypted' => 'encrypted:array',
        'last_seen_at' => 'datetime',
    ];

    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class);
    }
}
