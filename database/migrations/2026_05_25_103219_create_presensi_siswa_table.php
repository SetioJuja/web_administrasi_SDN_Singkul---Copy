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
        Schema::create('presensi_siswa', function (Blueprint $table) {
            $table->integer('id_presensi_siswa', true);
            $table->integer('id_siswa')->index('id_siswa');
            $table->date('tanggal');
            $table->integer('id_status')->index('id_status');
            $table->integer('id_tahun_ajaran')->index('fk_id_tahun_presensi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_siswa');
    }
};
