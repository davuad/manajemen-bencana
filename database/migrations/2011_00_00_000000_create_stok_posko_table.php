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
        Schema::create('stok_posko', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('posko_id');

            $table->string('barang_id', 10);

            $table->enum('kategori_distribusi', ['bencana', 'pasca_bencana']);

            $table->integer('jumlah_barang');

            $table->timestamps();

            $table->foreign('posko_id')
                ->references('id')->on('posko')
                ->onDelete('cascade');

            $table->foreign('barang_id')
                ->references('id_barang')->on('barang')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_posko');
    }
};
