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
        Schema::table('dokumen_administrasi', function (Blueprint $table) {
            $table->foreign(['id_tahun_ajaran'], 'fk_id_tahun_ajaran_dokumen')->references(['id_tahun_ajaran'])->on('tahun_ajaran')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumen_administrasi', function (Blueprint $table) {
            $table->dropForeign('fk_id_tahun_ajaran_dokumen');
        });
    }
};
