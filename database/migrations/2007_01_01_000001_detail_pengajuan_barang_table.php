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
        Schema::create('detail_pengajuan_barang', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pengajuan_barang_id')->constrained('pengajuan_barang')->cascadeOnDelete();
            $table->string('barang_id', 10);

            $table->foreign('barang_id')
                ->references('id_barang')
                ->on('barang')
                ->cascadeOnDelete();

            $table->enum('kategori_penerima', ['warga', 'pengungsi', 'relawan', 'anak-anak', 'lansia']);
            $table->integer('jumlah');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengajuan_barang');
    }
};
