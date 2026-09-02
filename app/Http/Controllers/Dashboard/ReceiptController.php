<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class ReceiptController extends Controller
{
    public function show(Transaction $transaction)
    {
        $tenant = app('currentTenant');

        // Ensure the transaction belongs to this tenant
        if ($transaction->tenant_id !== $tenant->id) {
            abort(403);
        }

        $transaction->load(['customer', 'offer', 'network']);

        return view('dashboard.receipts.show', compact('transaction', 'tenant'));
    }
}
