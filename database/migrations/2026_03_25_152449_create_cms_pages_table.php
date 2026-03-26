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
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('page_name');
            $table->string('slug')->unique();

            // Content
            $table->string('page_heading')->nullable();
            $table->text('short_desc')->nullable();
            $table->longText('description')->nullable();

            // Image
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();

            // Alignment ENUM
            $table->enum('image_align', ['left', 'right', 'center'])->default('center');

            // Status
            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_pages');
    }
};