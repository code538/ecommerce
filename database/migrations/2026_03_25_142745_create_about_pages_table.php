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
        Schema::create('about_pages', function (Blueprint $table) {
            $table->id();

            // Page Basic Info
            $table->string('page_name')->nullable();
            $table->text('page_desc')->nullable();
            $table->string('page_image')->nullable();

            // Section Content
            $table->string('heading')->nullable();
            $table->string('badge_text')->nullable();

            // Meta / SEO Fields
            $table->string('heading_meta')->nullable();
            $table->text('description')->nullable();
            $table->text('desc_meta')->nullable();

            // Media
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_pages');
    }
};