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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('subcategory_bg_color_menu')->nullable()->after('category_name_font_size_menu');
            $table->string('subcategory_text_color_menu')->nullable()->after('subcategory_bg_color_menu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['subcategory_bg_color_menu', 'subcategory_text_color_menu']);
        });
    }
};
