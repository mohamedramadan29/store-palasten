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
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('website_background')->nullable();
            $table->string('top_navbar_background')->nullable();
            $table->string('second_navbar_background')->nullable();
            $table->string('third_navbar_background')->nullable();
            $table->string('main_title_color')->nullable();
            $table->string('all_button_background')->nullable();
            $table->string('main_price_color')->nullable();
            $table->string('public_add_to_cart_background')->nullable();
            $table->string('public_add_to_cart_color')->nullable();
            $table->string('footer_background')->nullable();
            $table->string('footer_color')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colors');
    }
};
