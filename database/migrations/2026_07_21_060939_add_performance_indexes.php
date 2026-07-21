<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->index(['is_featured', 'is_active', 'sort_order'], 'programs_featured_active_sort_idx');
        });

        Schema::table('partner_logos', function (Blueprint $table) {
            $table->index(['type', 'is_active', 'sort_order'], 'logos_type_active_sort_idx');
        });

        Schema::table('university_partners', function (Blueprint $table) {
            $table->index(['is_active', 'country'], 'unipartners_active_country_idx');
        });

        Schema::table('faculty_insights', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'insights_active_sort_idx');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->index(['is_active', 'event_date'], 'events_active_date_idx');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'testimonials_active_sort_idx');
        });
    }

    public function down(): void
    {
        Schema::table('programs', fn(Blueprint $t) => $t->dropIndex('programs_featured_active_sort_idx'));
        Schema::table('partner_logos', fn(Blueprint $t) => $t->dropIndex('logos_type_active_sort_idx'));
        Schema::table('university_partners', fn(Blueprint $t) => $t->dropIndex('unipartners_active_country_idx'));
        Schema::table('faculty_insights', fn(Blueprint $t) => $t->dropIndex('insights_active_sort_idx'));
        Schema::table('events', fn(Blueprint $t) => $t->dropIndex('events_active_date_idx'));
        Schema::table('testimonials', fn(Blueprint $t) => $t->dropIndex('testimonials_active_sort_idx'));
    }
};
