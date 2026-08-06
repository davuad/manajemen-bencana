<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_barang_masuk', function (Blueprint $table) {
            $table->dropForeign(['id_barang_masuk']);
        });

        DB::statement("ALTER TABLE detail_barang_masuk MODIFY id_barang_masuk VARCHAR(30)");

        Schema::table('detail_barang_masuk', function (Blueprint $table) {
            $table->foreign('id_barang_masuk')
                  ->references('id_barang_masuk')
                  ->on('barang_masuk')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('detail_barang_masuk', function (Blueprint $table) {
            $table->dropForeign(['id_barang_masuk']);
        });

        DB::statement("ALTER TABLE detail_barang_masuk MODIFY id_barang_masuk VARCHAR(10)");

        Schema::table('detail_barang_masuk', function (Blueprint $table) {
            $table->foreign('id_barang_masuk')
                  ->references('id_barang_masuk')
                  ->on('barang_masuk')
                  ->onDelete('cascade');
        });
    }
};