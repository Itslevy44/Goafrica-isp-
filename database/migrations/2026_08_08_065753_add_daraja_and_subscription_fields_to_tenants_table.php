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
        Schema::table('tenants', function (Blueprint $table) {
            $table->timestamp('subscription_ends_at')->nullable();
            $table->string('mpesa_environment')->default('sandbox');
            $table->text('mpesa_consumer_key')->nullable();
            $table->text('mpesa_consumer_secret')->nullable();
            $table->text('mpesa_passkey')->nullable();
            $table->string('mpesa_shortcode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_ends_at',
                'mpesa_environment',
                'mpesa_consumer_key',
                'mpesa_consumer_secret',
                'mpesa_passkey',
                'mpesa_shortcode',
            ]);
        });
    }
};
