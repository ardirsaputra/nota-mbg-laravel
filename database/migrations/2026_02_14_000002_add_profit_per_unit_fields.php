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
        Schema::table('nota_items', function (Blueprint $table) {
            $table->integer('profit_per_unit')->default(0)->after('harga_satuan');
        });

        Schema::table('harga_barang_pokok', function (Blueprint $table) {
            $table->integer('profit_per_unit')->default(0)->after('harga_satuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nota_items', function (Blueprint $table) {
            $table->dropColumn('profit_per_unit');
        });

        Schema::table('harga_barang_pokok', function (Blueprint $table) {
            $table->dropColumn('profit_per_unit');
        });
    }
};
