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
        Schema::create('konten_umum', function (Blueprint $table) {
            $table->integer('id_konten_umum', true);
            $table->text('visi');
            $table->text('misi');
            $table->string('akreditasi')->nullable();
            $table->string('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('jam_operasional')->nullable();
            $table->integer('total_guru')->nullable();
            $table->integer('total_siswa')->nullable();
            $table->string('gambar_login')->nullable();
            $table->string('gambar_beranda')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konten_umum');
    }
};
