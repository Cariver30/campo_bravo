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
            if (!Schema::hasColumn('settings', 'show_cover_loyalty_card')) {
                $table->boolean('show_cover_loyalty_card')->default(true)->after('show_cta_vip');
            }
            if (!Schema::hasColumn('settings', 'cover_loyalty_label')) {
                $table->text('cover_loyalty_label')->nullable()->after('show_cover_loyalty_card');
            }
            if (!Schema::hasColumn('settings', 'cover_loyalty_title')) {
                $table->text('cover_loyalty_title')->nullable()->after('cover_loyalty_label');
            }
            if (!Schema::hasColumn('settings', 'cover_loyalty_description')) {
                $table->text('cover_loyalty_description')->nullable()->after('cover_loyalty_title');
            }

            $ctaKeys = ['menu', 'cafe', 'cocktails', 'events', 'reservations', 'vip'];
            foreach ($ctaKeys as $key) {
                $subtitleColumn = 'cover_cta_' . $key . '_subtitle';
                $copyColumn = 'cover_cta_' . $key . '_copy';
                $buttonColumn = 'cover_cta_' . $key . '_button_text';

                if (!Schema::hasColumn('settings', $subtitleColumn)) {
                    $table->text($subtitleColumn)->nullable()->after('cover_cta_' . $key . '_text_color');
                }
                if (!Schema::hasColumn('settings', $copyColumn)) {
                    $table->text($copyColumn)->nullable()->after($subtitleColumn);
                }
                if (!Schema::hasColumn('settings', $buttonColumn)) {
                    $table->text($buttonColumn)->nullable()->after($copyColumn);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'cover_loyalty_description')) {
                $table->dropColumn('cover_loyalty_description');
            }
            if (Schema::hasColumn('settings', 'cover_loyalty_title')) {
                $table->dropColumn('cover_loyalty_title');
            }
            if (Schema::hasColumn('settings', 'cover_loyalty_label')) {
                $table->dropColumn('cover_loyalty_label');
            }
            if (Schema::hasColumn('settings', 'show_cover_loyalty_card')) {
                $table->dropColumn('show_cover_loyalty_card');
            }

            $ctaKeys = ['menu', 'cafe', 'cocktails', 'events', 'reservations', 'vip'];
            foreach ($ctaKeys as $key) {
                $subtitleColumn = 'cover_cta_' . $key . '_subtitle';
                $copyColumn = 'cover_cta_' . $key . '_copy';
                $buttonColumn = 'cover_cta_' . $key . '_button_text';

                if (Schema::hasColumn('settings', $buttonColumn)) {
                    $table->dropColumn($buttonColumn);
                }
                if (Schema::hasColumn('settings', $copyColumn)) {
                    $table->dropColumn($copyColumn);
                }
                if (Schema::hasColumn('settings', $subtitleColumn)) {
                    $table->dropColumn($subtitleColumn);
                }
            }
        });
    }
};
