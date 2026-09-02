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
        Schema::table('offers', function (Blueprint $table) {
            if (!Schema::hasColumn('offers', 'is_multi_device')) {
                $table->boolean('is_multi_device')->default(false)->after('data_cap_mb');
            }
            if (!Schema::hasColumn('offers', 'max_devices')) {
                $table->integer('max_devices')->default(1)->after('is_multi_device');
            }
        });

        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'access_code')) {
                $table->string('access_code')->nullable()->after('gateway_ref');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn(['is_multi_device', 'max_devices']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['access_code']);
        });
    }
};
