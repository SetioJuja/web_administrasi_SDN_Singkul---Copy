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
        Schema::table('nilai_siswa', function (Blueprint $table) {
            $table->foreign(['id_siswa'], 'nilai_siswa_ibfk_1')->references(['id_siswa'])->on('siswa')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_mapel'], 'nilai_siswa_ibfk_2')->references(['id_mapel'])->on('mapel')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_kelas'], 'nilai_siswa_ibfk_3')->references(['id_kelas'])->on('kelas')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_siswa', function (Blueprint $table) {
            $table->dropForeign('nilai_siswa_ibfk_1');
            $table->dropForeign('nilai_siswa_ibfk_2');
            $table->dropForeign('nilai_siswa_ibfk_3');
        });
    }
};
