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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            // Foreign Key
            $table->foreignId('bencana_id')
                  ->constrained('bencana')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('pegawai_id');
            $table->foreign('pegawai_id')
                  ->references('id_pegawai')
                  ->on('pegawai')
                  ->onDelete('cascade');

            // Field utama
            $table->date('tanggal_layanan');
            $table->time('jam_mulai');
            $table->time('jam_selesai');

            $table->string('jenis_layanan', 100);
            $table->string('sarana', 50);
            $table->string('petugas_lapangan', 100);
            $table->string('lokasi_layanan', 150);
            $table->enum('status', ['dijadwalkan', 'selesai'])->default('dijadwalkan');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};