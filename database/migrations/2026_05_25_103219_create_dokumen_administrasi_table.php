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
        Schema::create('dokumen_administrasi', function (Blueprint $table) {
            $table->integer('id_dokumen', true);
            $table->string('judul_dokumen');
            $table->string('gambar')->nullable();
            $table->date('tanggal_upload');
            $table->string('keterangan')->nullable();
            $table->integer('id_tahun_ajaran')->index('fk_id_tahun_ajaran_dokumen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_administrasi');
    }
};
