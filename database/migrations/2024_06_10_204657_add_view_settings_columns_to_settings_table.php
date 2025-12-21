<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddViewSettingsColumnsToSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('text_color_cover')->nullable();
            $table->string('text_color_menu')->nullable();
            $table->decimal('card_opacity_cover', 3, 2)->nullable();
            $table->decimal('card_opacity_menu', 3, 2)->nullable();
            $table->string('font_family_cover')->nullable();
            $table->string('font_family_menu')->nullable();
            $table->string('button_color_cover')->nullable();
            $table->string('button_color_menu')->nullable();
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('text_color_cover');
            $table->dropColumn('text_color_menu');
            $table->dropColumn('card_opacity_cover');
            $table->dropColumn('card_opacity_menu');
            $table->dropColumn('font_family_cover');
            $table->dropColumn('font_family_menu');
            $table->dropColumn('button_color_cover');
            $table->dropColumn('button_color_menu');
        });
    }
}
