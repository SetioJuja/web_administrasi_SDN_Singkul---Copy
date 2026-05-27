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
        Schema::create('presensi_guru', function (Blueprint $table) {
            $table->integer('id_presensi_guru', true);
            $table->integer('id_guru')->nullable()->index('id_guru');
            $table->date('tanggal')->nullable();
            $table->integer('id_status')->nullable()->index('id_status');
            $table->integer('id_tahun_ajaran')->nullable()->index('id_tahun_ajaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_guru');
    }
};
