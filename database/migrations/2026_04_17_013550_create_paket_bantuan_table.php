<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket_bantuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posko_id')
                ->constrained('posko')
                ->cascadeOnDelete();

            $table->string('nama_paket', 50);
            $table->text('keterangan')->nullable();
            $table->enum('status', ['aktif', 'non aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket_bantuan');
    }
};
