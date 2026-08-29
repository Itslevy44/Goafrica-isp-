<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class PayoutAccount extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'country_code', 'method', 'account_identifier',
        'account_name', 'verified_at', 'is_active'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
