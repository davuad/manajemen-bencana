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
    Schema::create('detail_pengambilan', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('pengambilan_id');
        $table->unsignedBigInteger('barang_id');

        $table->integer('jumlah_diambil');
         $table->text('keterangan')->nullable();

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('detail_pengambilan');
}
};