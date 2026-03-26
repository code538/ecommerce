<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();

            // Page identification
            $table->string('page_key')->unique(); 
            // examples: home, about, contact, blog, product-detail

            // Basic SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            // Open Graph / Social
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();

            // Advanced SEO
            $table->string('canonical_url')->nullable();
            $table->string('robots')->default('index,follow');

            // Schema (JSON-LD)
            $table->longText('schema_json')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};
