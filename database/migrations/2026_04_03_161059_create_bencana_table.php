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
        Schema::create('bencana', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_bencana')->onDelete('cascade');
            $table->unsignedBigInteger('pengaduan_id')->nullable();
            $table->unsignedBigInteger('desa_id')->nullable();
            $table->date('tanggal');
            $table->integer('jumlah_korban')->default(0);
            $table->string('tingkat_kerusakan', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bencana');
    }
};
