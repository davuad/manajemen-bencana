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
        Schema::create('kebutuhan_pengaduan', function (Blueprint $table) {
            $table->id('id');

            $table->foreignId('pengaduan_bencana_id')
                ->constrained('pengaduan_bencana')
                ->onDelete('cascade');

            $table->enum('dapur_umum', ['Butuh', 'Tidak'])->default('Tidak');
            $table->enum('psikososial', ['Butuh', 'Tidak'])->default('Tidak');
            $table->enum('logistik_rentan', ['Butuh', 'Tidak'])->default('Tidak');
            $table->enum('logistik_makanan', ['Butuh', 'Tidak'])->default('Tidak');
            $table->enum('logistik_penampungan', ['Butuh', 'Tidak'])->default('Tidak');

            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kebutuhan_pengaduan');
    }
};
