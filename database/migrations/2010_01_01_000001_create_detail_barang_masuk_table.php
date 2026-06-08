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
        Schema::create('detail_barang_masuk', function (Blueprint $table) {
            $table->string('id_detail_barang_masuk', 10)->primary();

            $table->string('id_barang_masuk', 10);
            $table->foreign('id_barang_masuk')
                ->references('id_barang_masuk')
                ->on('barang_masuk')
                ->onDelete('cascade');

            $table->string('id_barang', 10);
            $table->foreign('id_barang')
                ->references('id_barang')
                ->on('barang')
                ->onDelete('cascade');

            $table->integer('jumlah');
            $table->string('satuan', 20);
            $table->string('kondisi_barang', 30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_barang_masuk');
    }
};
