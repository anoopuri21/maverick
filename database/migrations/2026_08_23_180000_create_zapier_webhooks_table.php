<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zapier_webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('event_key');
            $table->string('label')->nullable();
            $table->text('url');
            $table->boolean('is_enabled')->default(true);
            $table->timestamp('last_triggered_at')->nullable();
            $table->string('last_status')->nullable();
            $table->text('last_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zapier_webhooks');
    }
};
