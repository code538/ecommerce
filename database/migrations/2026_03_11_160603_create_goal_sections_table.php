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
        Schema::create('goal_sections', function (Blueprint $table) {
            $table->id();

            $table->string('badge_text')->nullable(); // SHOP BY YOUR GOALS
            $table->string('title'); // Achieve Your Fitness Goals
            $table->text('description')->nullable();

            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goal_sections');
    }
};
