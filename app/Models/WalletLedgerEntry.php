<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class WalletLedgerEntry extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'entry_type', 'amount_minor', 'currency',
        'reference_type', 'reference_id', 'balance_after_minor'
    ];
}
