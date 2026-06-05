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
        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->string('id_barang_masuk', 30)->primary();

            $table->date('tgl_masuk');

            $table->string('id_sumber', 10);
            $table->foreign('id_sumber')
                ->references('id_sumber')
                ->on('sumber_barang_masuk')
                ->onDelete('cascade');

            $table->string('id_pegawai', 10)->nullable();
            $table->string('id_gudang', 10)->nullable();
            $table->string('id_bencana', 10)->nullable();

            $table->string('no_dokumen', 50);
            $table->string('nama_penerima', 100);

            $table->string('status', 20)
                    ->default('diproses');

            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_masuk');
    }
};
