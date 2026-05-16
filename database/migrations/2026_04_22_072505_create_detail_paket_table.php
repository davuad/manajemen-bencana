<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_paket', function (Blueprint $table) {
            $table->id();

            $table->foreignId('paket_bantuan_id')
                ->constrained('paket_bantuan')
                ->onDelete('cascade');

            $table->string('barang_id', 10);

            $table->foreign('barang_id')
                ->references('id_barang')
                ->on('barang')
                ->onDelete('cascade');

            $table->integer('jumlah');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_paket');
    }
};
