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
        Schema::table('presensi_siswa', function (Blueprint $table) {
            $table->foreign(['id_tahun_ajaran'], 'fk_id_tahun_presensi')->references(['id_tahun_ajaran'])->on('tahun_ajaran')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_siswa'], 'presensi_siswa_ibfk_1')->references(['id_siswa'])->on('siswa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_status'], 'presensi_siswa_ibfk_2')->references(['id_status'])->on('status_presensi')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi_siswa', function (Blueprint $table) {
            $table->dropForeign('fk_id_tahun_presensi');
            $table->dropForeign('presensi_siswa_ibfk_1');
            $table->dropForeign('presensi_siswa_ibfk_2');
        });
    }
};
