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
        Schema::table('events', function (Blueprint $table) {
            if (! Schema::hasColumn('events', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('title');
            }
            if (! Schema::hasColumn('events', 'display_image')) {
                $table->string('display_image')->nullable()->after('location');
            }
            if (! Schema::hasColumn('events', 'gallery_images')) {
                $table->json('gallery_images')->nullable()->after('display_image');
            }
        });

        // Backfill slug for existing records if any exist without slug
        $events = \App\Models\Event::whereNull('slug')->orWhere('slug', '')->get();
        foreach ($events as $event) {
            $baseSlug = \Illuminate\Support\Str::slug($event->title ?: 'event-' . $event->id);
            $slug = $baseSlug;
            $count = 1;
            while (\App\Models\Event::where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
                $slug = $baseSlug . '-' . $count;
                $count++;
            }
            $event->timestamps = false;
            $event->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('events', 'slug')) {
                $columnsToDrop[] = 'slug';
            }
            if (Schema::hasColumn('events', 'display_image')) {
                $columnsToDrop[] = 'display_image';
            }
            if (Schema::hasColumn('events', 'gallery_images')) {
                $columnsToDrop[] = 'gallery_images';
            }
            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
