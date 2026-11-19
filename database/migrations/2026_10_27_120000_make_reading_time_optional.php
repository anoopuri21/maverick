<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = ['insights', 'blog_posts'];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (! Schema::hasTable($tableName) || ! Schema::hasColumn($tableName, 'reading_time_minutes')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->unsignedInteger('reading_time_minutes')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            if (! Schema::hasTable($tableName) || ! Schema::hasColumn($tableName, 'reading_time_minutes')) {
                continue;
            }

            DB::table($tableName)
                ->whereNull('reading_time_minutes')
                ->update(['reading_time_minutes' => 1]);

            Schema::table($tableName, function (Blueprint $table) {
                $table->unsignedInteger('reading_time_minutes')->default(1)->nullable(false)->change();
            });
        }
    }
};
