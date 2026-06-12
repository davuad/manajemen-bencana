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
       Schema::create('distribusi', function (Blueprint $table) {
    $table->id();
    $table->foreignId('bencana_id')->constrained('bencana')->cascadeOnDelete();
    $table->foreignId('posko_id')->constrained('posko')->cascadeOnDelete();

    $table->date('tanggal_distribusi');
    $table->string('lokasi_distribusi');
    $table->string('kendaraan');
    $table->string('nama_supir');
    $table->string('nomor_kendaraan');

    $table->enum('kategori_distribusi', ['bencana', 'pasca_bencana']);
    $table->enum('status', ['pending', 'dikirim', 'selesai']);

    $table->text('keterangan')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusi');
    }
};