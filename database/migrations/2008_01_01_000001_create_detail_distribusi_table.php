<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_distribusi', function (Blueprint $table) {

            $table->id();

            $table->foreignId('distribusi_id')
                ->constrained('distribusi')
                ->onDelete('cascade');

            // DIUBAH
            $table->foreignId('detail_barang_keluar_id')
                ->constrained('detail_barang_keluar')
                ->onDelete('cascade');

            $table->integer('jumlah_kirim');

            $table->string('satuan');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_distribusi');
    }
};