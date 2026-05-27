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
        Schema::create('komponen_penilaian', function (Blueprint $table) {
            $table->integer('id_komponen', true);
            $table->integer('id_mapel')->nullable();
            $table->integer('id_guru')->nullable();
            $table->string('nama_komponen', 100)->nullable();
            $table->integer('bobot')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komponen_penilaian');
    }
};
