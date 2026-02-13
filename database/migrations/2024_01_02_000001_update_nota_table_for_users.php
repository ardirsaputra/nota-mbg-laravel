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
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_admin_nota')->default(true)->after('profit_insight');
            $table->unsignedBigInteger('cloned_from_id')->nullable()->after('is_admin_nota');

            $table->foreign('cloned_from_id')->references('id')->on('nota')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nota', function (Blueprint $table) {
            $table->dropForeign(['cloned_from_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'is_admin_nota', 'cloned_from_id']);
        });
    }
};
