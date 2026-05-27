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
        Schema::table('jadwal_mengajar', function (Blueprint $table) {
            $table->foreign(['id_guru'], 'jadwal_mengajar_ibfk_1')->references(['id_guru'])->on('pegawai')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_kelas'], 'jadwal_mengajar_ibfk_2')->references(['id_kelas'])->on('kelas')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_mapel'], 'jadwal_mengajar_ibfk_3')->references(['id_mapel'])->on('mapel')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_tahun_ajaran'], 'jadwal_mengajar_ibfk_4')->references(['id_tahun_ajaran'])->on('tahun_ajaran')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_mengajar', function (Blueprint $table) {
            $table->dropForeign('jadwal_mengajar_ibfk_1');
            $table->dropForeign('jadwal_mengajar_ibfk_2');
            $table->dropForeign('jadwal_mengajar_ibfk_3');
            $table->dropForeign('jadwal_mengajar_ibfk_4');
        });
    }
};
