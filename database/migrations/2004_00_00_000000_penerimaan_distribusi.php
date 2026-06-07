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
         Schema::create('penerima_distribusi', function (Blueprint $table) {

            $table->id('penerima_id');

            $table->foreignId('detail_distribusi_id')
                    ->constrained('detail_distribusi')
                    ->onDelete('cascade');

            $table->string('nama_penerima', 100);
            $table->string('jabatan', 100);
            $table->string('instansi', 100);
            $table->string('alamat', 150);
            $table->string('no_hp', 15);

            $table->string('status', 100);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerima_distribusi');
    }
};
