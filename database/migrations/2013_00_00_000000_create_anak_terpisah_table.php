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
        Schema::create('anak_terpisah', function (Blueprint $table) {
            $table->id();
            // relasi ke bencana
            //$table->unsignedBigInteger('bencana_id')->nullable();
            
            //data anak
            $table->string('foto_anak', 255);
            $table->string('nama_anak', 100);
            $table->enum('jenis_kelamin', ['L','P']);

            $table->integer('umur')->nullable();
            $table->date('tanggal_lahir')->nullable();

            $table->text('alamat_asal')->nullable();
            $table->string('lokasi_ditemukan', 150);
            $table->date('tanggal_ditemukan');

            $table->string('nama_ortu_wali', 100)->nullable();
            $table->string('kontak_keluarga', 20)->nullable();

            $table->enum('status_anak', [
                'belum_dijemput',
                'sudah_dijemput',
                'dalam_proses'
            ])->default('belum_dijemput');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anak_terpisah');
    }
};
