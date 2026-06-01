<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('petugas', function (Blueprint $table) {
            $table->id();

            $table->string('nama_petugas', 100);

            $table->enum('jabatan', [
                'Admin',
                'Relawan',
                'Koordinator'
            ]);

            $table->string('no_hp', 15);

            // 🔹 TAMBAHAN
            $table->year('tahun');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');

            $table->unsignedBigInteger('posko_id');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('posko_id')
                ->references('id')
                ->on('posko')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petugas');
    }
};