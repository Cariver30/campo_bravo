<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->text('cover_cta_menu_button_color')->nullable()->after('cover_cta_menu_button_text');
            $table->text('cover_cta_cafe_button_color')->nullable()->after('cover_cta_cafe_button_text');
            $table->text('cover_cta_cocktails_button_color')->nullable()->after('cover_cta_cocktails_button_text');
            $table->text('cover_cta_events_button_color')->nullable()->after('cover_cta_events_button_text');
            $table->text('cover_cta_reservations_button_color')->nullable()->after('cover_cta_reservations_button_text');
            $table->text('cover_cta_vip_button_color')->nullable()->after('cover_cta_vip_button_text');

            $table->text('featured_price_color')->nullable()->after('featured_tab_text_color');

            $table->text('social_icon_bg_color')->nullable()->after('instagram_url');
            $table->text('social_icon_icon_color')->nullable()->after('social_icon_bg_color');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'cover_cta_menu_button_color',
                'cover_cta_cafe_button_color',
                'cover_cta_cocktails_button_color',
                'cover_cta_events_button_color',
                'cover_cta_reservations_button_color',
                'cover_cta_vip_button_color',
                'featured_price_color',
                'social_icon_bg_color',
                'social_icon_icon_color',
            ]);
        });
    }
};
