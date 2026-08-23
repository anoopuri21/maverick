<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_success_stories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('role')->nullable();
            $table->text('quote')->nullable();
            $table->string('photo')->nullable();
            $table->unsignedBigInteger('photo_asset_id')->nullable();
            $table->unsignedTinyInteger('stars')->default(5);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('student_success_videos', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('role')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->unsignedBigInteger('thumbnail_url_asset_id')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });

        $this->seedFromSettings();
    }

    public function down(): void
    {
        Schema::dropIfExists('student_success_videos');
        Schema::dropIfExists('student_success_stories');
    }

    private function seedFromSettings(): void
    {
        $decode = function (?string $name): array {
            $raw = DB::table('settings')
                ->where('group', 'student_success_page')
                ->where('name', $name)
                ->value('payload');

            if ($raw === null || $raw === '') {
                return [];
            }

            $value = is_string($raw) ? json_decode($raw, true) : $raw;

            if (is_string($value)) {
                $value = json_decode($value, true);
            }

            return array_values(is_array($value) ? $value : []);
        };

        foreach ($decode('stories') as $i => $item) {
            if (! is_array($item)) {
                continue;
            }

            DB::table('student_success_stories')->insert([
                'name' => $item['name'] ?? null,
                'role' => $item['role'] ?? null,
                'quote' => $item['quote'] ?? null,
                'photo' => $item['photo'] ?? null,
                'photo_asset_id' => $item['photo_asset_id'] ?? null,
                'stars' => max(0, min(5, (int) ($item['stars'] ?? 5))),
                'sort_order' => $i,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($decode('video_stories') as $i => $item) {
            if (! is_array($item)) {
                continue;
            }

            DB::table('student_success_videos')->insert([
                'name' => $item['name'] ?? null,
                'role' => $item['role'] ?? null,
                'youtube_url' => $item['youtube_url'] ?? null,
                'thumbnail_url' => $item['thumbnail_url'] ?? null,
                'thumbnail_url_asset_id' => $item['thumbnail_url_asset_id'] ?? null,
                'sort_order' => $i,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};
