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
        Schema::table('top_nav_bars', function (Blueprint $table) {
            $table->string('text_color')->nullable();
            $table->string('button_background')->nullable();
            $table->string('button_color')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('top_nav_bars', function (Blueprint $table) {
            $table->dropColumn(['text_color', 'button_background', 'button_color']);
        });
    }
};
