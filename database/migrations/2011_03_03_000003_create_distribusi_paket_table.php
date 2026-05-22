<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distribusi_paket', function (Blueprint $table) {
            $table->id();

            $table->foreignId('warga_terdampak_id')
                ->constrained('warga_terdampak')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('paket_bantuan_id')
                ->constrained('paket_bantuan')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->integer('jumlah_paket');
            $table->date('tanggal_distribusi');

            $table->foreignId('pegawai_id')
                ->constrained('pegawai', 'id_pegawai')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->enum('status_distribusi', [
                'Proses Penyaluran',
                'Sudah disalurkan'
            ])->default('Proses Penyaluran');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribusi_paket');
    }
};
