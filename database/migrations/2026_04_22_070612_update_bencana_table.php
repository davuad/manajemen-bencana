<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bencana', function (Blueprint $table) {
            $table->string('nama_bencana')->after('id');
            $table->enum('status_bencana', ['berlangsung', 'selesai'])
                  ->default('berlangsung');
            $table->dropColumn('jumlah_korban');
        });
    }

    public function down(): void
    {
        Schema::table('bencana', function (Blueprint $table) {

            $table->dropColumn('nama_bencana');
            $table->dropColumn('status_bencana');

            $table->integer('jumlah_korban')->default(0);
        });
    }
};
