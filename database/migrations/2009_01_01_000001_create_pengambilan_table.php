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
        Schema::create('pengambilan', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->unsignedBigInteger('barang_id');
            $table->unsignedBigInteger('bencana_id');
            $table->unsignedBigInteger('petugas_id');
            $table->unsignedBigInteger('posko_id');

            // Data utama
            $table->date('tanggal_pengambilan');
            $table->integer('jumlah_ambil');
            $table->string('tujuan', 100);

            // Status
            $table->enum('status', [
                'Ditangani',
                'Selesai',
                'Dibatalkan'
            ])->default('Ditangani');

            $table->timestamps();

            // Foreign Key
            $table->foreign('barang_id')
                  ->references('id')
                  ->on('barang')
                  ->onDelete('cascade');

            $table->foreign('bencana_id')
                  ->references('id')
                  ->on('bencana')
                  ->onDelete('cascade');

            $table->foreign('petugas_id')
                  ->references('id')
                  ->on('petugas')
                  ->onDelete('cascade');

            $table->foreign('posko_id')
                  ->references('id')
                  ->on('posko')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengambilan');
    }
};