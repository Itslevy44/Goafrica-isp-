<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payout_accounts', function (Blueprint $table) {
            // Link payout account to a specific network (null = applies to all networks as fallback)
            $table->foreignId('network_id')->nullable()->after('tenant_id')
                  ->constrained('networks')->onDelete('cascade');
            // M-Pesa Daraja credentials stored per payout account / network
            $table->string('mpesa_environment')->nullable()->after('account_name');
            $table->string('mpesa_consumer_key')->nullable()->after('mpesa_environment');
            $table->string('mpesa_consumer_secret')->nullable()->after('mpesa_consumer_key');
            $table->string('mpesa_passkey')->nullable()->after('mpesa_consumer_secret');
            $table->string('mpesa_shortcode')->nullable()->after('mpesa_passkey');
        });

        // Widen country_code to varchar(10) to handle full country names gracefully
        Schema::table('payout_accounts', function (Blueprint $table) {
            $table->string('country_code', 10)->change();
        });
    }

    public function down(): void
    {
        Schema::table('payout_accounts', function (Blueprint $table) {
            $table->dropForeign(['network_id']);
            $table->dropColumn(['network_id', 'mpesa_environment', 'mpesa_consumer_key',
                                'mpesa_consumer_secret', 'mpesa_passkey', 'mpesa_shortcode']);
            $table->string('country_code', 3)->change();
        });
    }
};
