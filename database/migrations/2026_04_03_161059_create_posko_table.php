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
            $table->id();

            $table->string('nama_posko', 100);
            $table->string('lokasi', 150);
            $table->date('tanggal_dibuat');
            $table->string('status')->default('aktif');

            $table->foreignId('pengaduan_bencana_id')
                ->constrained('pengaduan_bencana')
                ->cascadeOnDelete();

            $table->foreignId('desa_id')
                ->constrained('desa')
                ->cascadeOnDelete();

            $table->timestamps();
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
