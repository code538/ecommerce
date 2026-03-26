<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();

            // Basic site identity
            $table->string('site_name')->nullable();
            $table->string('site_web_logo')->nullable();        // header logo
            $table->string('site_mobile_logo')->nullable();
            $table->string('site_logo_alt')->nullable(); 
            $table->string('site_favicon')->nullable();
            $table->string('punch_line')->nullable();


            // Contact details
            $table->string('phone')->nullable();
            $table->string('landline')->nullable();
            $table->string('whats_app')->nullable();
            $table->string('email')->nullable();
            $table->string('fax')->nullable();

            // Address
            $table->string('street_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip')->nullable();

            // Social media
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->string('pinterest')->nullable();

            // SEO / misc
            $table->string('sitemap_url')->nullable();

            // Status
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
