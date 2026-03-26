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
        Schema::create('why_chose_us', function (Blueprint $table) {
            $table->id();

            $table->string('badge')->nullable();
            $table->string('title');
            $table->text('description')->nullable();

            $table->string('title_meta')->nullable();
            $table->text('description_meta')->nullable();

            $table->boolean('status')->default(1);
            $table->integer('short_order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('why_chose_us');
    }
};