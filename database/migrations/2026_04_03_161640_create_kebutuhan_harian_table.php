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
        Schema::create('kebutuhan_harian', function (Blueprint $table) {
            $table->increments('id_kebutuhan');

            $table->unsignedInteger('id_dapur');

            $table->date('tanggal');
            $table->integer('jumlah_warga');
            $table->integer('porsi_per_orang');
            $table->integer('total_porsi');

            $table->timestamps();

            $table->foreign('id_dapur')
                ->references('id_dapur')
                ->on('dapur_umum')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kebutuhan_harian');
    }
};
