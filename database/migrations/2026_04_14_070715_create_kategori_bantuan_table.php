<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // biar aman kalau tabel belum ada
        if (!Schema::hasTable('kategori_bantuan')) {

            Schema::create('kategori_bantuan', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('id_sumber');

                $table->string('nama_kategori', 50);
                $table->string('keterangan', 150)->nullable();

                $table->timestamps();

                // RELASI
                $table->foreign('id_sumber')
                      ->references('id')
                      ->on('sumber')
                      ->onDelete('cascade');
            });

        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_bantuan');
    }
};