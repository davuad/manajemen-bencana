<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bencana', function (Blueprint $table) {
            $table->id();

            $table->string('nama_bencana');

            $table->foreignId('kategori_id')
                ->constrained('kategori_bencana')
                ->onDelete('cascade');

            $table->unsignedBigInteger('pengaduan_id')->nullable();
            $table->unsignedBigInteger('desa_id')->nullable();

            $table->date('tanggal');
            $table->string('tingkat_kerusakan', 50)->nullable();

            $table->enum('status_bencana', ['berlangsung', 'selesai'])
                ->default('berlangsung');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bencana');
    }
};
