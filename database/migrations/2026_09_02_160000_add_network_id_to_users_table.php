<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Network a staff member is assigned to (null = all networks / admin)
            $table->foreignId('network_id')->nullable()->after('tenant_id')
                  ->constrained('networks')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['network_id']);
            $table->dropColumn('network_id');
        });
    }
};
