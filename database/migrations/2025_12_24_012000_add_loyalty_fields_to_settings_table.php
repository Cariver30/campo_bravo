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
            $table->unsignedInteger('loyalty_points_per_visit')->default(10)->after('cta_image_reservations');
            $table->text('loyalty_terms')->nullable()->after('loyalty_points_per_visit');
            $table->text('loyalty_email_copy')->nullable()->after('loyalty_terms');
            $table->string('tab_label_menu')->nullable()->after('loyalty_email_copy');
            $table->string('tab_label_cocktails')->nullable()->after('tab_label_menu');
            $table->string('tab_label_wines')->nullable()->after('tab_label_cocktails');
            $table->string('tab_label_events')->nullable()->after('tab_label_wines');
            $table->string('tab_label_loyalty')->nullable()->after('tab_label_events');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'loyalty_points_per_visit',
                'loyalty_terms',
                'loyalty_email_copy',
                'tab_label_menu',
                'tab_label_cocktails',
                'tab_label_wines',
                'tab_label_events',
                'tab_label_loyalty',
            ]);
        });
    }
};
