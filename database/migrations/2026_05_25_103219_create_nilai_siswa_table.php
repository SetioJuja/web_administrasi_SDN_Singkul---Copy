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
        Schema::create('nilai_siswa', function (Blueprint $table) {
            $table->integer('id_nilai', true);
            $table->integer('id_siswa')->index('id_siswa');
            $table->integer('id_mapel')->index('id_mapel');
            $table->integer('id_kelas')->index('id_kelas');
            $table->decimal('nilai_tugas', 5)->nullable()->default(0);
            $table->decimal('nilai_uts', 5)->nullable()->default(0);
            $table->decimal('nilai_uas', 5)->nullable()->default(0);
            $table->decimal('total', 5)->nullable()->default(0);
            $table->double('nilai_keterampilan')->nullable();
            $table->text('deskripsi_pengetahuan')->nullable();
            $table->text('deskripsi_keterampilan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_siswa');
    }
};
