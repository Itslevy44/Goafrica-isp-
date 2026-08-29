<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Settlement extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'payout_account_id', 'period_start', 'period_end',
        'gross_amount_minor', 'commission_amount_minor', 'net_amount_minor',
        'currency', 'status', 'gateway_ref', 'initiated_at', 'paid_at'
    ];

    protected $casts = [
        'period_start' => 'datetime',
        'period_end' => 'datetime',
        'initiated_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function payoutAccount(): BelongsTo
    {
        return $this->belongsTo(PayoutAccount::class);
    }
}
