<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('media_assets')) {
            Schema::table('media_assets', function (Blueprint $table) {
                if (! Schema::hasColumn('media_assets', 'used')) {
                    $table->boolean('used')->default(false)->after('disk_env');
                    $table->index('used', 'media_assets_used_idx');
                }

                if (! Schema::hasColumn('media_assets', 'is_duplicate')) {
                    $table->boolean('is_duplicate')->default(false)->after('used');
                }
            });
        }

        if (! Schema::hasTable('media_recycle_logs')) {
            Schema::create('media_recycle_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('media_asset_id')->nullable()->index();
                $table->string('cloudinary_public_id')->index();
                $table->string('url');
                $table->string('hash', 64)->nullable();
                $table->string('folder')->nullable();
                $table->string('disk_env')->nullable();
                $table->string('original_name')->nullable();
                $table->boolean('deleted_from_cloudinary')->default(false);
                $table->json('payload')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('media_recycle_logs')) {
            Schema::dropIfExists('media_recycle_logs');
        }

        if (Schema::hasTable('media_assets')) {
            Schema::table('media_assets', function (Blueprint $table) {
                if (Schema::hasColumn('media_assets', 'is_duplicate')) {
                    $table->dropColumn('is_duplicate');
                }

                if (Schema::hasColumn('media_assets', 'used')) {
                    $table->dropIndex('media_assets_used_idx');
                    $table->dropColumn('used');
                }
            });
        }
    }
};
