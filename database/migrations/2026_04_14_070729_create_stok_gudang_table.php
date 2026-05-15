<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('stok_gudang', function (Blueprint $table) {
            $table->id();

            $table->foreignId('gudang_id')
                ->constrained('gudang')
                ->onDelete('cascade');

            $table->string('barang_id', 10);

            $table->foreign('barang_id')
                ->references('id_barang')
                ->on('barang')
                ->onDelete('cascade');

            $table->integer('jumlah_stok');
            $table->string('kondisi_barang', 30);
            $table->string('keterangan', 150)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_gudang');
    }
};