<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternetSession extends Model
{
    use BelongsToTenant;

    protected $table = 'internet_sessions';

    protected $fillable = [
        'tenant_id', 'network_id', 'device_id', 'customer_id',
        'mac_address', 'ip_address', 'source_type', 'source_id',
        'started_at', 'ends_at', 'status'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class);
    }
}
