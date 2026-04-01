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
            $table->boolean('marketer_system_status')->default(0)->after('id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('user_type')->default('customer')->after('email'); // customer, marketer
            $table->string('phone')->nullable()->after('user_type');
            $table->string('status')->default('active')->after('phone'); // active, inactive
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('marketer_price', 10, 2)->nullable()->after('purches_price');
        });

        Schema::table('product_vartions', function (Blueprint $table) {
            $table->decimal('marketer_price', 10, 2)->nullable()->after('purchase_price');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_marketer_order')->default(0)->after('id');
            $table->unsignedBigInteger('marketer_id')->nullable()->after('is_marketer_order');
            $table->decimal('total_profit', 10, 2)->default(0)->after('grand_total');
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->decimal('marketer_price', 10, 2)->default(0)->after('product_price');
            $table->decimal('profit', 10, 2)->default(0)->after('marketer_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('public_settings', function (Blueprint $table) {
            $table->dropColumn('marketer_system_status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_type', 'phone', 'status']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('marketer_price');
        });

        Schema::table('product_vartions', function (Blueprint $table) {
            $table->dropColumn('marketer_price');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['is_marketer_order', 'marketer_id', 'total_profit']);
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn(['marketer_price', 'profit']);
        });
    }
};
