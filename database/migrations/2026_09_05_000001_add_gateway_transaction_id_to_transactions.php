<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Safaricom CheckoutRequestID stored separately from gateway_ref
            // gateway_ref is updated to the receipt number on success
            // gateway_transaction_id holds the original CheckoutRequestID for reconciliation
            $table->string('gateway_transaction_id')->nullable()->after('gateway_ref');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('gateway_transaction_id');
        });
    }
};
