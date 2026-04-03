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
        Schema::create('dapur_umum', function (Blueprint $table) {
            $table->increments('id_dapur');

            $table->unsignedInteger('id_posko');

            $table->integer('kapasitas_warga');
            $table->integer('jumlah_warga');
            $table->string('penanggung_jawab', 100);

            $table->timestamps();

            $table->foreign('id_posko')
                ->references('id_posko')
                ->on('posko')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dapur_umum');
    }
};
