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
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->unsignedBigInteger('pengambilan_id');
            $table->unsignedBigInteger('petugas_id');
            $table->unsignedBigInteger('posko_id');

            // Data Pengembalian
            $table->date('tanggal_pengembalian');
            $table->integer('jumlah_kembali');
            $table->text('keterangan')->nullable();

            // Status
            $table->enum('status', [
                'Ditangani',
                'Selesai',
                'Dibatalkan'
            ])->default('Ditangani');

            $table->timestamps();

            // Foreign Key
            $table->foreign('pengambilan_id')
                ->references('id')
                ->on('pengambilan')
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
        Schema::dropIfExists('pengembalian');
    }
};