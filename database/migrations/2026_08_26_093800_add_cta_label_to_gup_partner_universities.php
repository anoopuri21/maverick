<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gup_partner_universities', function (Blueprint $table) {
            if (! Schema::hasColumn('gup_partner_universities', 'cta_label')) {
                $table->string('cta_label')->nullable()->after('cta_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('gup_partner_universities', function (Blueprint $table) {
            if (Schema::hasColumn('gup_partner_universities', 'cta_label')) {
                $table->dropColumn('cta_label');
            }
        });
    }
};
