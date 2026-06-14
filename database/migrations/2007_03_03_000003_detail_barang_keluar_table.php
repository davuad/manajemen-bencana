<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_barang_keluar', function (Blueprint $table) {
            $table->id();

            $table->foreignId('barang_keluar_id')->constrained('barang_keluar')->cascadeOnDelete();
            $table->string('barang_id', 10);

            $table->foreign('barang_id')
                ->references('id_barang')
                ->on('barang')
                ->cascadeOnDelete();

            $table->integer('jumlah');
            $table->integer('jumlah_keluar')->nullable();
            $table->string('catatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_barang_keluar');
    }
};
