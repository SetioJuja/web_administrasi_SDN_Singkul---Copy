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
        Schema::table('pegawai_jabatan', function (Blueprint $table) {
            $table->foreign(['id_guru'], 'pegawai_jabatan_ibfk_1')->references(['id_guru'])->on('pegawai')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['id_jabatan'], 'pegawai_jabatan_ibfk_2')->references(['id_jabatan'])->on('jabatan')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawai_jabatan', function (Blueprint $table) {
            $table->dropForeign('pegawai_jabatan_ibfk_1');
            $table->dropForeign('pegawai_jabatan_ibfk_2');
        });
    }
};
