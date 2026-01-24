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
        Schema::create('public_settings', function (Blueprint $table) {
            $table->id();
            $table->string('website_name')->default('اسم الموقع');
            $table->string('website_logo')->nullable();
            $table->string('website_short_desc')->nullable();
            $table->string('website_description')->nullable();
            $table->string('website_keywords')->nullable();
            $table->tinyInteger('status')->default('1');
            $table->string('main_color')->default('#FE6C2F');
            $table->string('second_color')->default('#FFDACD');
            $table->string('website_currency')->nullable();
            $table->string('website_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_settings');
    }
};
