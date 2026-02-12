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
        Schema::create('nota_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_id')->constrained('nota')->onDelete('cascade');
            $table->string('uraian');
            $table->string('satuan', 50)->default('Kg');
            $table->integer('qty')->default(1);
            $table->integer('harga_satuan')->default(0);
            $table->integer('subtotal')->default(0);
            $table->timestamps();

            $table->index('nota_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_items');
    }
};
