<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('insights')) {
            Schema::table('insights', function (Blueprint $table) {
                $table->index(['is_featured', 'published_at'], 'insights_featured_published_idx');
            });
        }

        if (Schema::hasTable('media_assets')) {
            Schema::table('media_assets', function (Blueprint $table) {
                $table->index('deleted_at', 'media_assets_deleted_at_idx');
                $table->index(['disk_env', 'folder'], 'media_assets_disk_folder_idx');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('insights')) {
            Schema::table('insights', fn (Blueprint $t) => $t->dropIndex('insights_featured_published_idx'));
        }

        if (Schema::hasTable('media_assets')) {
            Schema::table('media_assets', function (Blueprint $t) {
                $t->dropIndex('media_assets_deleted_at_idx');
                $t->dropIndex('media_assets_disk_folder_idx');
            });
        }
    }
};
