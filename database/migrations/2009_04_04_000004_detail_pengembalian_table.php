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
        Schema::create('detail_pengembalian', function (Blueprint $table) {
            $table->id(); // primary key

            $table->unsignedBigInteger('pengembalian_barang_id');
            $table->string('barang_id', 10);

            $table->text('jumlah_barang_dikembalikan');
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat']);

            $table->timestamps();

            // Foreign Key
            $table->foreign('pengembalian_barang_id')
                  ->references('id')->on('pengembalian')
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
        Schema::dropIfExists('detail_pengembalian');
    }
};