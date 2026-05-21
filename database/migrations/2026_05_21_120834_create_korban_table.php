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
        Schema::create('korban', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bencana_id')
                ->constrained('bencana');

            $table->foreignId('posko_id')
                ->constrained('posko');

            $table->foreignId('user_id')
                ->constrained('user');    

            $table->string('nama');
            $table->string('nik')->nullable()->unique();

            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan']);
            $table->integer('umur');

            $table->string('alamat');

            $table->string('lokasi_kejadian');
            $table->date('tanggal_kejadian');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('korban');
    }
};
