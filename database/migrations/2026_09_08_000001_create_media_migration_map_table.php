<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Authoritative old-account → new-account mapping for the Cloudinary
 * account migration (Phase 1). One row per old public_id:
 * migrated = own upload done, shared = same-hash row reusing the canonical
 * upload, skipped/failed = needs review or retry. Phase 2 cutover reads
 * ONLY this table — never blind cloud-name swaps.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('media_migration_map')) {
            Schema::create('media_migration_map', function (Blueprint $table) {
                $table->id();
                $table->string('old_public_id')->unique();
                $table->string('new_public_id')->nullable();
                $table->text('old_url')->nullable();
                $table->text('new_url')->nullable();
                $table->unsignedBigInteger('bytes')->nullable();
                $table->unsignedBigInteger('media_asset_id')->nullable()->index();
                $table->string('source', 20)->default('asset');
                $table->string('source_ref')->nullable();
                $table->string('status', 20)->index();
                $table->string('reason')->nullable();
                $table->text('last_error')->nullable();
                $table->unsignedInteger('attempts')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('media_migration_map');
    }
};
