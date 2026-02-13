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
        Schema::table('nota', function (Blueprint $table) {
            $table->foreignId('toko_id')->nullable()->after('no')->constrained('toko')->onDelete('set null');
            $table->string('nama_toko_manual')->nullable()->after('toko_id');
            $table->text('alamat_toko_manual')->nullable()->after('nama_toko_manual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nota', function (Blueprint $table) {
            $table->dropForeign(['toko_id']);
            $table->dropColumn(['toko_id', 'nama_toko_manual', 'alamat_toko_manual']);
        });
    }
};
