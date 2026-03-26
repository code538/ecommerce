<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('short_videos', function (Blueprint $table) {
            $table->id();

            $table->string('video_title')->nullable();
            $table->string('video')->nullable();
            $table->string('youtube_link')->nullable();

            $table->string('button_name')->nullable();
            $table->string('button_url')->nullable();

            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('short_videos');
    }
};
