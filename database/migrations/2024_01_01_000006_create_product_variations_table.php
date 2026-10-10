<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // size, color, wrapping
            $table->string('name');
            $table->string('value')->nullable();
            $table->decimal('price_adjustment', 12, 2)->default(0);
            $table->integer('stock')->nullable();
            $table->string('sku')->nullable();
            $table->timestamps();

            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variations');
    }
};
