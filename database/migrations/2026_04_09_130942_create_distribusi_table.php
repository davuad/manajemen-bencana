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
        Schema::create('distribusi', function (Blueprint $table) {
            $table->id();

            // Kolom FK
            $table->foreignId('barang_keluar_id')->nullable()
                  ->constrained('barang_keluar') // mengacu ke id di barang_keluar
                  ->cascadeOnDelete();

            $table->foreignId('bencana_id')->nullable();

            $table->foreignId('posko_id')->nullable();

            $table->date('tanggal_distribusi');
            $table->string('lokasi_distribusi', 100);
            $table->string('kendaraan', 100);
            $table->string('nama_supir', 100);
            $table->string('nomor_kendaraan', 100);
            $table->string('keterangan', 255)->nullable();
            $table->string('kategori_distribusi', 50);
            $table->string('status', 20);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusi');
    }
};
