<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('our_story_testimonials', function (Blueprint $table) {
            if (! Schema::hasColumn('our_story_testimonials', 'organisation')) {
                $table->string('organisation')->nullable()->after('name');
            }
            if (! Schema::hasColumn('our_story_testimonials', 'position')) {
                $table->string('position')->nullable()->after('organisation');
            }
            if (! Schema::hasColumn('our_story_testimonials', 'country')) {
                $table->string('country')->nullable()->after('position');
            }
        });
    }

    public function down(): void
    {
        Schema::table('our_story_testimonials', function (Blueprint $table) {
            $drop = array_values(array_filter(
                ['organisation', 'position', 'country'],
                fn (string $column) => Schema::hasColumn('our_story_testimonials', $column)
            ));

            if ($drop !== []) {
                $table->dropColumn($drop);
            }
        });
    }
};
