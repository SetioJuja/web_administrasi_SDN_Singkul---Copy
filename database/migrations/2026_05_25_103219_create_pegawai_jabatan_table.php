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
        Schema::create('pegawai_jabatan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_guru')->nullable()->index('id_guru');
            $table->integer('id_jabatan')->nullable()->index('id_jabatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai_jabatan');
    }
};
