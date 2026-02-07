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
        Schema::table('product_vartions', function (Blueprint $table) {
            $table->decimal('purchase_price', 8, 2)->default(0)->after('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_vartions', function (Blueprint $table) {
            $table->dropColumn('purchase_price');
        });
    }
};
