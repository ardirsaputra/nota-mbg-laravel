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
        Schema::create('nota', function (Blueprint $table) {
            $table->id();
            $table->string('no', 100);
            $table->date('tanggal');
            $table->string('nama_toko')->nullable();
            $table->text('alamat')->nullable();
            $table->integer('total')->default(0);
            $table->boolean('is_locked')->default(false);
            $table->boolean('profit_insight')->default(true);
            $table->timestamps();

            $table->index('no');
            $table->index('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota');
    }
};
