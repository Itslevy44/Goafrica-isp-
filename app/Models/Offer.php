<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'network_id', 'name', 'duration_minutes', 
        'price_minor', 'currency', 'data_cap_mb', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class);
    }
}
