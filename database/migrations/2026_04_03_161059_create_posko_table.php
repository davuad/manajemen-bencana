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
        Schema::create('posko', function (Blueprint $table) {
            $table->increments('id_posko'); 

            $table->unsignedInteger('id_pengaduan');
            $table->string('nama_posko', 100);
            $table->string('lokasi', 150);
            $table->unsignedInteger('id_desa');

            $table->date('tanggal_dibuat');

            $table->timestamps();

            $table->foreign('id_pengaduan')
                ->references('id_pengaduan')
                ->on('pengaduan_bencana')
                ->cascadeOnDelete();

            $table->foreign('id_desa')
                ->references('id_desa')
                ->on('desa')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posko');
    }
};
