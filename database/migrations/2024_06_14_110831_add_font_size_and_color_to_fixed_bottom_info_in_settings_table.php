<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFontSizeAndColorToFixedBottomInfoInSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('fixed_bottom_font_size')->default(14);
            $table->string('fixed_bottom_font_color')->default('#000000');
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('fixed_bottom_font_size');
            $table->dropColumn('fixed_bottom_font_color');
        });
    }
}

