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
        'price_minor', 'currency', 'data_cap_mb', 'is_active',
        'is_multi_device', 'max_devices',
    ];

    protected $casts = [
        'is_active'        => 'boolean',
        'is_multi_device'  => 'boolean',
        'max_devices'      => 'integer',
    ];

    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class);
    }

    /**
     * Human-readable duration string e.g. "1 Hour", "7 Days"
     */
    public function getDurationLabelAttribute(): string
    {
        $mins = $this->duration_minutes;
        if ($mins < 60)  return "{$mins} Min" . ($mins !== 1 ? 's' : '');
        if ($mins < 1440) {
            $h = round($mins / 60, 1);
            return "{$h} Hour" . ($h !== 1.0 ? 's' : '');
        }
        $d = round($mins / 1440, 1);
        return "{$d} Day" . ($d !== 1.0 ? 's' : '');
    }
}
