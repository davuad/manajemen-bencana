<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_keluar', function (Blueprint $table) {
            $table->id();

            $table->foreignId('gudang_id')->constrained('gudang')->cascadeOnDelete();
            $table->foreignId('pengajuan_barang_id')->constrained('pengajuan_barang')->cascadeOnDelete();
            $table->unsignedBigInteger('petugas_gudang_id');

            $table->foreign('petugas_gudang_id')
                ->references('id_pegawai')
                ->on('pegawai')
                ->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('user')->nullOnDelete();

            $table->date('tgl_keluar');
            $table->enum('status_proses', ['diproses', 'dikirim', 'selesai', 'dibatalkan']);
            $table->string('catatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_keluar');
    }
};
