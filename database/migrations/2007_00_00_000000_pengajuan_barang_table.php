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
        Schema::create('pengajuan_barang', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bencana_id')->constrained('bencana')->cascadeOnDelete();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();

            $table->date('tgl_pengajuan');
            $table->enum('status_pengajuan', ['pending', 'disetujui', 'ditolak']);

            $table->foreignId('created_by')->nullable()->constrained('user')->nullOnDelete(); 
            $table->foreignId('updated_by')->nullable()->constrained('user')->nullOnDelete();

            $table->foreignId('acc_ketua_id')
                ->nullable()
                ->constrained('pegawai', 'id')
                ->nullOnDelete();

            $table->date('tgl_persetujuan')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('catatan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_barang');
    }
};
