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
        
            $table->unsignedBigInteger('pengaduan_bencana_id');
            $table->unsignedBigInteger('desa_id');
            $table->date('tanggal');                  
            $table->unsignedInteger('jumlah_korban'); 
            $table->string('tingkat_kerusakan', 50); 

            $table->timestamps();

             $table->foreign('pengaduan_bencana_id')
                  ->references('id')
                  ->on('pengaduan_bencana')
                  ->onDelete('cascade');

             $table->foreign('desa_id')
                  ->references('id')
                  ->on('desa')
                  ->onDelete('cascade');                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
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