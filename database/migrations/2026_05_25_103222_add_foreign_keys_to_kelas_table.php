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
        Schema::table('kelas', function (Blueprint $table) {
            $table->foreign(['id_guru'], 'fk_id_guru')->references(['id_guru'])->on('pegawai')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_tahun_ajaran'], 'fk_id_tahun_ajaran')->references(['id_tahun_ajaran'])->on('tahun_ajaran')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign('fk_id_guru');
            $table->dropForeign('fk_id_tahun_ajaran');
        });
    }
};
