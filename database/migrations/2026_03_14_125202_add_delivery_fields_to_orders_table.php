<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->string('receiver_name')->nullable()->after('phone');
            $table->string('receiver_phone')->nullable()->after('receiver_name');

            $table->text('address')->nullable()->after('receiver_phone');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('pincode')->nullable()->after('state');

        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropColumn([
                'receiver_name',
                'receiver_phone',
                'address',
                'city',
                'state',
                'pincode'
            ]);

        });
    }
};
