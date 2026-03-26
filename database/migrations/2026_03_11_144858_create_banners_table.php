<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();

            $table->string('badge_text')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();

            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();

            $table->string('video')->nullable();
            $table->string('youtube_url')->nullable();
            $table->enum('banner_type', ['image', 'video', 'youtube'])->default('image');

            $table->string('button1_text')->nullable();
            $table->string('button1_link')->nullable();

            $table->string('button2_text')->nullable();
            $table->string('button2_link')->nullable();

            // SEO fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            $table->boolean('status')->default(1);
            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};