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
            $table->boolean('disable_background_cover')->default(false)->after('background_image_cover');
            $table->boolean('disable_background_menu')->default(false)->after('background_image_menu');
            $table->boolean('disable_background_cocktails')->default(false)->after('background_image_cocktails');
            $table->boolean('disable_background_wines')->default(false)->after('background_image_wines');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'disable_background_cover',
                'disable_background_menu',
                'disable_background_cocktails',
                'disable_background_wines',
            ]);
        });
    }
};
