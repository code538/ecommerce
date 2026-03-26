<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();

            $table->string('faq_type', 100)->index()
                  ->comment('Module like blog, hrms_software, billing');

            $table->string('faq_slug', 150)->index()
                  ->comment('Page slug like blog, hrms-software');

            $table->string('faq_question');
            $table->string('question_meta')->nullable();

            $table->longText('faq_answer');
            $table->string('faq_answer_meta')->nullable();

            $table->boolean('is_active')->default(true)->index();

            $table->timestamps();

            // Optional but recommended
            $table->index(['faq_type', 'faq_slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
