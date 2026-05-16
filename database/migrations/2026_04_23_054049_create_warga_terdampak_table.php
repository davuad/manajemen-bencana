<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warga_terdampak', function (Blueprint $table) {
            $table->id();

            $table->string('no_kk', 20)->unique();
            $table->string('nik_kepala_keluarga', 20)->unique();
            $table->string('nama_kepala_keluarga', 50);
            $table->text('alamat');

            $table->foreignId('desa_id')
                ->constrained('desa')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('bencana_id')
                ->constrained('bencana')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedInteger('jumlah_anggota');
            $table->date('tanggal_pendataan');

            $table->enum('jenis_bantuan', [
                'Bantuan Saat Bencana',
                'Bantuan Pasca Bencana',
            ]);

            $table->enum('status_penyaluran', [
                'Belum diproses',
                'Proses Penyaluran',
                'Sudah disalurkan',
            ]);

            $table->date('tanggal_penyaluran')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warga_terdampak');
    }
};
