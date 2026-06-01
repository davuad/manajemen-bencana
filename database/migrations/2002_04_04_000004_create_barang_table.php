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
    $table->id(); 

    $table->string('nama_barang', 100);
    $table->string('id_jenis_barang', 10);
    $table->integer('stok');
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
