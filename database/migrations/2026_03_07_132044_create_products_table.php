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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('name_meta')->nullable();
            $table->string('tag_line')->nullable();
            $table->boolean('premium_product')->default(0);

            $table->decimal('price',10,2);
            $table->decimal('sale_price',10,2)->nullable();

            $table->integer('stock')->default(0);

            $table->float('rating')->default(0);
            $table->integer('review_count')->default(0);

            $table->text('description')->nullable();
            $table->text('description_meta')->nullable();

            $table->text('shipping_policy')->nullable();
            $table->text('shipping_policy_meta')->nullable();

            $table->text('return_policy')->nullable();
            $table->text('return_policy_meta')->nullable();

            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
