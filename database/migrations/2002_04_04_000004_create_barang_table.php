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
        Schema::create('barang', function (Blueprint $table) {
            $table->string('id_barang', 10)->primary();
            $table->string('nama_barang', 100);

            $table->string('id_jenis_barang', 10);
            $table->foreign('id_jenis_barang')
                    ->references('id_jenis_barang')
                    ->on('jenis_barang')
                    ->onDelete('cascade');

            $table->integer('stok')->default(0);
            $table->string('satuan', 20);
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
