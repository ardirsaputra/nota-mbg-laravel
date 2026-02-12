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
        Schema::create('harga_barang_pokok', function (Blueprint $table) {
            $table->id();
            $table->text('uraian');
            $table->string('kategori', 100)->default('Umum');
            $table->string('satuan', 50);
            $table->double('nilai_satuan')->default(1.0);
            $table->integer('harga_satuan');
            $table->timestamps();

            $table->index('kategori');
            $table->index('satuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_barang_pokok');
    }
};
