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
        Schema::create('penjemputan_anak', function (Blueprint $table) {
            $table->id();
            // foreign key
            $table->foreignId('anak_id')->constrained('anak_terpisah')->onDelete('cascade');
            $table->foreignId('penjemput_id')->constrained('penjemput')->onDelete('cascade');
           
            //relasi ke petugas
            $table->foreignId('petugas_id')
                    ->constrained('petugas')
                    ->onDelete('cascade');
            //data
            $table->date('tanggal_penjemputan');
            $table->enum('status_verifikasi', ['menunggu', 'valid', 'ditolak'])->default('menunggu');

            $table->text('catatan')->nullable();
            $table->string('bukti_dokumen', 255)->nullable();
            $table->string('berita_acara', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjemputan_anak');
    }
};
