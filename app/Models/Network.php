<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Network extends Model
{
    use BelongsToTenant;

    protected $fillable = ['tenant_id', 'region_id', 'name', 'slug', 'currency', 'status'];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }
}
