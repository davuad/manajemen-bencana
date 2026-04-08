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
        Schema::create('pengaduan_bencana', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('id_kategori');
            $table->unsignedInteger('id_user');

            $table->text('desa');
            $table->text('deskripsi');

            $table->string('status_pengaduan', 30)->default('BELUM_DITANGANI');
            $table->text('keterangan_verifikasi')->nullable();

            $table->dateTime('tanggal_selesai')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduan_bencana');
    }
};
