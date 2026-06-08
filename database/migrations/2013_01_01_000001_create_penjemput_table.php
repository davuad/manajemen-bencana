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
        Schema::create('penjemput', function (Blueprint $table) {
            $table->id();

            //data penjemput
            $table->string('nama_penjemput', 100);
            $table->string('nik', 20);
            $table->string('hubungan_dengan_anak', 50);
            $table->text('alamat')->nullable();
            $table->string('no_hp', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjemput_');
    }
};
