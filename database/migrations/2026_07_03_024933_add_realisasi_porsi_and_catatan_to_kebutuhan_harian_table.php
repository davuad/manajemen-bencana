<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kebutuhan_harian', function (Blueprint $table) {
            $table->integer('realisasi_porsi')->nullable()
                ->after('total_porsi');

            $table->text('catatan')
                ->nullable()
                ->after('realisasi_porsi');
        });
    }

    public function down(): void
    {
        Schema::table('kebutuhan_harian', function (Blueprint $table) {
            $table->dropColumn([
                'realisasi_porsi',
                'catatan'
            ]);
        });
    }
};
