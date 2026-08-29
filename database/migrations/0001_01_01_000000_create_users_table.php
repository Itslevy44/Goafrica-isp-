<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('country');
            $table->string('default_currency');
            $table->string('status')->default('active'); // active, suspended
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('staff'); // admin, staff
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('country_code', 3)->unique(); // KE, TZ, UG
            $table->string('name');
            $table->string('currency', 3); // KES, TZS, UGX
            $table->string('timezone');
            $table->timestamps();
        });

        Schema::create('networks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('region_id')->constrained('regions');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('currency', 3);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('network_id')->constrained('networks')->onDelete('cascade');
            $table->string('type')->default('mikrotik'); // mikrotik, custom
            $table->string('name');
            $table->string('ip_address');
            $table->integer('api_port')->default(8728);
            $table->text('credentials_encrypted');
            $table->string('status')->default('unknown'); // online, offline, unknown
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
        });

        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('network_id')->constrained('networks')->onDelete('cascade');
            $table->string('name');
            $table->integer('duration_minutes');
            $table->integer('price_minor'); // in cents
            $table->string('currency', 3);
            $table->integer('data_cap_mb')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['network_id', 'name']);
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('phone')->index();
            $table->timestamp('first_seen_at')->useCurrent();
            $table->timestamp('last_seen_at')->useCurrent();
            $table->timestamps();

            $table->unique(['tenant_id', 'phone']);
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('network_id')->constrained('networks')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('offer_id')->constrained('offers')->onDelete('cascade');
            $table->string('gateway'); // mpesa, flutterwave, mock
            $table->string('gateway_ref')->unique();
            $table->integer('amount_minor');
            $table->string('currency', 3);
            $table->string('status')->default('pending'); // pending, success, failed
            $table->text('raw_payload')->nullable();
            $table->decimal('commission_rate', 5, 2)->default(0.10);
            $table->integer('commission_amount_minor');
            $table->integer('net_amount_minor');
            $table->timestamps();
        });

        Schema::create('payout_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('country_code', 3);
            $table->string('method'); // mpesa_till, mpesa_paybill, mobile_wallet, bank_account
            $table->string('account_identifier');
            $table->string('account_name');
            $table->timestamp('verified_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('payout_account_id')->constrained('payout_accounts');
            $table->timestamp('period_start')->nullable();
            $table->timestamp('period_end')->nullable();
            $table->integer('gross_amount_minor');
            $table->integer('commission_amount_minor');
            $table->integer('net_amount_minor');
            $table->string('currency', 3);
            $table->string('status')->default('pending'); // pending, processing, paid, failed
            $table->string('gateway_ref')->nullable();
            $table->timestamp('initiated_at')->useCurrent();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('wallet_ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('entry_type'); // sale_credit, payout_debit, adjustment
            $table->integer('amount_minor');
            $table->string('currency', 3);
            $table->string('reference_type')->nullable(); // transaction, settlement
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->integer('balance_after_minor');
            $table->timestamps();
        });

        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('network_id')->constrained('networks')->onDelete('cascade');
            $table->string('code')->unique();
            $table->string('type')->default('time'); // time, money
            $table->integer('value'); // minutes or minor amount
            $table->integer('max_uses')->default(1);
            $table->integer('uses_count')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable(); // user_id
            $table->timestamps();
        });

        Schema::create('internet_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('network_id')->constrained('networks')->onDelete('cascade');
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('mac_address')->index();
            $table->string('ip_address')->nullable();
            $table->string('source_type'); // transaction, voucher
            $table->unsignedBigInteger('source_id'); // transaction_id or voucher_id
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('ends_at')->nullable();
            $table->string('status')->default('active'); // active, expired, terminated
            $table->timestamps();
        });

        Schema::create('voucher_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained('vouchers')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('session_id')->constrained('internet_sessions')->onDelete('cascade');
            $table->timestamp('redeemed_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('device_command_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('command');
            $table->text('response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_command_logs');
        Schema::dropIfExists('voucher_redemptions');
        Schema::dropIfExists('internet_sessions');
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('wallet_ledger_entries');
        Schema::dropIfExists('settlements');
        Schema::dropIfExists('payout_accounts');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('offers');
        Schema::dropIfExists('devices');
        Schema::dropIfExists('networks');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('tenants');
    }
};
