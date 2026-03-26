<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();

            $table->string('full_name');
            //$table->string('company_name')->nullable();
            $table->string('email')->index();
            $table->string('phone_number')->nullable();

            $table->string('subject');
            $table->text('message');

            // For admin tracking
            $table->enum('status', ['new', 'replied', 'closed'])
                  ->default('new')
                  ->index();
            $table->text('minutes')->nullable();      

            $table->timestamps();
        });
    }

  
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
