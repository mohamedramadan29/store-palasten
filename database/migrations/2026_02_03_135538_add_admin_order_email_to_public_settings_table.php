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
        Schema::table('public_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('public_settings', 'admin_order_email')) {
                $table->string('admin_order_email')->nullable()->after('website_address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('public_settings', function (Blueprint $table) {
            $table->dropColumn('admin_order_email');
        });
    }
};
