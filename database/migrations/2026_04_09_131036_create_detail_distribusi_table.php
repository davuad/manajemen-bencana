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
        Schema::create('detail_distribusi', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('distribusi_id');
    $table->unsignedBigInteger('barang_keluar_id');

    $table->integer('jumlah');
    $table->string('satuan', 20);
    $table->string('keterangan', 100)->nullable();
    $table->timestamps();

    $table->foreign('distribusi_id')
          ->references('id')->on('distribusi')
          ->onDelete('cascade');

    $table->foreign('barang_keluar_id')
          ->references('id')->on('barang_keluar')
          ->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_distribusi');
    }
};
