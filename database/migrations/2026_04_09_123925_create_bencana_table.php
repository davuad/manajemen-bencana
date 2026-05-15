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

            $table->unsignedBigInteger('kategori_id'); 
            $table->foreignId('pengaduan_bencana_id')  // FK ke pengaduan_bencana
                  ->constrained('pengaduan_bencana')
                  ->cascadeOnDelete();
            $table->foreignId('desa_id')               // FK ke desa
                  ->constrained('desa')
                  ->cascadeOnDelete();

            $table->date('tanggal');                  
            $table->unsignedInteger('jumlah_korban'); 
            $table->string('tingkat_kerusakan', 50); 

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
