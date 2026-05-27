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
        Schema::table('presensi_guru', function (Blueprint $table) {
            $table->foreign(['id_guru'], 'presensi_guru_ibfk_1')->references(['id_guru'])->on('pegawai')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_status'], 'presensi_guru_ibfk_2')->references(['id_status'])->on('status_presensi')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_tahun_ajaran'], 'presensi_guru_ibfk_3')->references(['id_tahun_ajaran'])->on('tahun_ajaran')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi_guru', function (Blueprint $table) {
            $table->dropForeign('presensi_guru_ibfk_1');
            $table->dropForeign('presensi_guru_ibfk_2');
            $table->dropForeign('presensi_guru_ibfk_3');
        });
    }
};
