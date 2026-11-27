<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('media_assets')) {
            return;
        }

        Schema::table('media_assets', function (Blueprint $table) {
            if (! Schema::hasColumn('media_assets', 'source_url')) {
                $table->string('source_url')->nullable()->after('url');
            }

            if (! Schema::hasColumn('media_assets', 'storage_disk')) {
                $table->string('storage_disk', 64)->nullable()->after('disk_env');
            }

            if (! Schema::hasColumn('media_assets', 'storage_path')) {
                $table->string('storage_path')->nullable()->after('storage_disk');
                $table->index(['storage_disk', 'storage_path'], 'media_assets_storage_path_idx');
            }
        });

        // Existing Cloudinary IDs remain as legacy audit metadata, while new
        // server-hosted uploads have no Cloudinary public ID at all.
        Schema::table('media_assets', function (Blueprint $table) {
            $table->string('cloudinary_public_id')->nullable()->change();
        });

        if (Schema::hasTable('media_recycle_logs') && Schema::hasColumn('media_recycle_logs', 'cloudinary_public_id')) {
            Schema::table('media_recycle_logs', function (Blueprint $table) {
                // Self-hosted uploads have no remote public ID.
                $table->string('cloudinary_public_id')->nullable()->change();
            });
        }

        // Some seeded testimonials were always local public files but carried
        // a synthetic Cloudinary ID for the old media-library schema. Do not
        // make routine cleanup contact Cloudinary for those local-only rows.
        DB::table('media_assets')
            ->where('url', 'like', '/%')
            ->where('url', 'not like', '//%')
            ->whereNull('source_url')
            ->update(['cloudinary_public_id' => null]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('media_assets')) {
            return;
        }

        Schema::table('media_assets', function (Blueprint $table) {
            if (Schema::hasColumn('media_assets', 'storage_path')) {
                $table->dropIndex('media_assets_storage_path_idx');
                $table->dropColumn('storage_path');
            }

            if (Schema::hasColumn('media_assets', 'storage_disk')) {
                $table->dropColumn('storage_disk');
            }

            if (Schema::hasColumn('media_assets', 'source_url')) {
                $table->dropColumn('source_url');
            }
        });
    }
};
