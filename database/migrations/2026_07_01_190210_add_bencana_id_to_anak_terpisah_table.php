<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anak_terpisah', function (Blueprint $table) {

            $table->foreignId('bencana_id')
                ->after('id')
                ->constrained('bencana')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('anak_terpisah', function (Blueprint $table) {

            $table->dropForeign(['bencana_id']);
            $table->dropColumn('bencana_id');

        });
    }
};