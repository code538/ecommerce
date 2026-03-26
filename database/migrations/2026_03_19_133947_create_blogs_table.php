<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            // Blog title
            $table->string('title');
            $table->string('title_slug')->unique()->index();
            $table->string('title_meta')->nullable();

            // Descriptions
            $table->text('short_desc')->nullable();
            $table->string('short_desc_meta')->nullable();

            $table->longText('long_desc');
            $table->string('long_desc_meta')->nullable();

            // Media
            $table->string('web_image')->nullable();
            $table->string('mobile_image')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('youtube_link')->nullable();

            // Status
            $table->boolean('is_active')->default(true)->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
