<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('areas'); // list of area names
            $table->decimal('delivery_fee', 12, 2)->default(0);
            $table->decimal('min_order', 12, 2)->default(0);
            $table->json('available_slots')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('delivery_slots', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('time_start');
            $table->time('time_end');
            $table->integer('max_orders')->default(10);
            $table->integer('current_orders')->default(0);
            $table->timestamps();

            $table->unique(['date', 'time_start', 'time_end']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_slots');
        Schema::dropIfExists('delivery_zones');
    }
};
