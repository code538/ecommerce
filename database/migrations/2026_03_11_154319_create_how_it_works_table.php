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
        Schema::create('how_it_works', function (Blueprint $table) {
            $table->id();

            $table->string('section_title');
            $table->string('section_subtitle')->nullable();

            $table->string('tab_name')->nullable(); // Adjustable Dumbbells etc

            $table->string('youtube_url')->nullable();
            $table->string('video_title')->nullable();

            $table->string('step1')->nullable();
            $table->string('step2')->nullable();
            $table->string('step3')->nullable();
            $table->string('step4')->nullable();

            $table->string('feature1')->nullable();
            $table->string('feature2')->nullable();
            $table->string('feature3')->nullable();
            $table->string('feature4')->nullable();

            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();

            $table->boolean('status')->default(1);
            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('how_it_works');
    }
};
