<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anak_terpisah', function (Blueprint $table) {

            $table->string('nama_bapak')->nullable()->after('nama_anak');
            $table->string('nama_ibu')->nullable()->after('nama_bapak');

            $table->dropColumn('nama_ortu_wali');
        });
    }

    public function down(): void
    {
        Schema::table('anak_terpisah', function (Blueprint $table) {

            $table->string('nama_ortu_wali')->nullable();

            $table->dropColumn([
                'nama_bapak',
                'nama_ibu'
            ]);
        });
    }
};