<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->text('wine_filter_panel_bg_color')->nullable()->after('card_bg_color_wines');
            $table->text('wine_filter_panel_text_color')->nullable()->after('wine_filter_panel_bg_color');
            $table->text('wine_filter_input_bg_color')->nullable()->after('wine_filter_panel_text_color');
            $table->text('wine_filter_input_text_color')->nullable()->after('wine_filter_input_bg_color');
            $table->text('wine_filter_chip_bg_color')->nullable()->after('wine_filter_input_text_color');
            $table->text('wine_filter_chip_text_color')->nullable()->after('wine_filter_chip_bg_color');

            $table->text('floating_bar_bg_menu')->nullable()->after('button_color_menu');
            $table->text('floating_bar_button_color_menu')->nullable()->after('floating_bar_bg_menu');
            $table->text('floating_bar_bg_cocktails')->nullable()->after('button_color_cocktails');
            $table->text('floating_bar_button_color_cocktails')->nullable()->after('floating_bar_bg_cocktails');
            $table->text('floating_bar_bg_wines')->nullable()->after('button_color_wines');
            $table->text('floating_bar_button_color_wines')->nullable()->after('floating_bar_bg_wines');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'wine_filter_panel_bg_color',
                'wine_filter_panel_text_color',
                'wine_filter_input_bg_color',
                'wine_filter_input_text_color',
                'wine_filter_chip_bg_color',
                'wine_filter_chip_text_color',
                'floating_bar_bg_menu',
                'floating_bar_button_color_menu',
                'floating_bar_bg_cocktails',
                'floating_bar_button_color_cocktails',
                'floating_bar_bg_wines',
                'floating_bar_button_color_wines',
            ]);
        });
    }
};
