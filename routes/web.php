<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
});

Route::view('/dashboard', 'dashboard');


Route::get('/pegawai', function () {
    return view('pegawai');
});

Route::get('/mapel', function () {
    return view('mapel');
});

Route::get('/tahun_ajaran', function () {
    return view('tahun_ajaran');
});

Route::get('/kelas', function () {
    return view('kelas');
});
Route::get('/dokumen', function () {
    return view('dokumen');
});

Route::get('/siswa', function () {
    return view('siswa');
});

Route::get('/jadwal_mengajar', function () {
    return view('jadwal_mengajar');
});

Route::get('/presensi_guru', function () {
    return view('presensi_guru');
});

Route::get('/pengumuman', function () {
    return view('pengumuman');
});

Route::get('/jabatan', function () {
    return view('jabatan');
});

Route::get('/lihat_pengumuman', function () {
    return view('lihat_pengumuman');
});

Route::get('/lihat_jadwal', function () {
    return view('lihat_jadwal');
});

Route::get('/lihat_presensi_me', function () {
    return view('lihat_presensi');
});

Route::get('/presensi_siswa', function () {
    return view('presensi_siswa');
});

Route::get('/sp_guru', function () {
    return view('sp_guru');
});

Route::get('/sdokumen', function () {
    return view('sdokumen');
});

Route::get('/sguru', function () {
    return view('sguru');
});

Route::get('/skelas', function () {
    return view('skelas');
});

Route::get('/mkomponen', function () {
    return view('komponen_penilaian');
});

Route::get('/stugas', function () {
    return view('tugas');
});

Route::get('/snilai', function () {
    return view('nilai_siswa');
});

Route::get('/dsnilai', function () {
    return view('data_nilai');
});

Route::get('/konten_umum', function () {
    return view('kelola_konten_umum');
});

Route::get('/rapor', function () {
    return view('raport');
});

Route::get('/aila', function () {
    return view('aila');
});