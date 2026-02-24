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
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->string('reference_type'); // 'order', 'manual', 'adjustment', 'cancel'
            $table->unsignedBigInteger('reference_id')->nullable(); // order_id or other reference
            $table->unsignedBigInteger('product_id');
            $table->string('product_name'); // Store product name at time of log
            $table->integer('quantity_before'); // Stock quantity before movement
            $table->integer('quantity_change'); // Positive for addition, negative for subtraction
            $table->integer('quantity_after'); // Stock quantity after movement
            $table->decimal('unit_cost', 10, 2)->default(0); // Cost per unit at time of movement
            $table->decimal('total_cost', 10, 2)->default(0); // Total cost of movement
            $table->string('movement_type'); // 'sale', 'cancel', 'manual_add', 'manual_subtract', 'adjustment'
            $table->text('reason')->nullable(); // Reason for manual movements
            $table->unsignedBigInteger('user_id')->nullable(); // Who made the change
            $table->string('user_name')->nullable(); // Store user name at time of log
            $table->string('ip_address')->nullable(); // IP address for audit
            $table->json('metadata')->nullable(); // Additional data like order details
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['reference_type', 'reference_id']);
            $table->index('product_id');
            $table->index('movement_type');
            $table->index('created_at');
            $table->index('user_id');
            
            // Foreign keys
            $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
