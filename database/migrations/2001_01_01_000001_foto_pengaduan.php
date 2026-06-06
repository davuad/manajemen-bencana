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
        Schema::create('foto_pengaduan', function (Blueprint $table) {
            $table->id('id');

            $table->foreignId('pengaduan_bencana_id')
                ->constrained('pengaduan_bencana')
                ->onDelete('cascade');

            $table->string('file_foto');
            $table->string('keterangan', 100)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_pengaduan');
    }
};
