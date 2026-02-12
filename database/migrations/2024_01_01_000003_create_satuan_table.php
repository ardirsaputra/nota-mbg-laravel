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
        Schema::create('satuan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_satuan', 50)->unique();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Insert initial data
        DB::table('satuan')->insert([
            ['nama_satuan' => 'Kg', 'keterangan' => 'Kilogram', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'Gram', 'keterangan' => 'Gram', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'Liter', 'keterangan' => 'Liter', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'Ml', 'keterangan' => 'Mililiter', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'Pcs', 'keterangan' => 'Pieces / Buah', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'Pack', 'keterangan' => 'Pak / Kemasan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'Dus', 'keterangan' => 'Kotak / Karton', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'Ton', 'keterangan' => 'Ton', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'Karung', 'keterangan' => 'Karung', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'Ball', 'keterangan' => 'Bal / Bale', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('satuan');
    }
};
