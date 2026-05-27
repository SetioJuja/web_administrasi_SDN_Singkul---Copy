<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\KontenUmumController;
use App\Http\Controllers\API\PegawaiController;
use App\Http\Controllers\API\JabatanController;
use App\Http\Controllers\API\MapelController;
use App\Http\Controllers\API\TahunAjaranController;
use App\Http\Controllers\API\KelasController;
use App\Http\Controllers\API\DokumenAdministrasiController;
use App\Http\Controllers\API\SiswaController;
use App\Http\Controllers\API\JadwalMengajarController;
use App\Http\Controllers\API\PresensiGuruController;
use App\Http\Controllers\API\StatusPresensiController;
use App\Http\Controllers\API\PengumumanController;
use App\Http\Controllers\API\PresensiSiswaController;
use App\Http\Controllers\API\KomponenPenilaianController;
use App\Http\Controllers\API\NilaiSiswaController;
use App\Http\Controllers\API\TugasController;
use App\Http\Controllers\API\NilaiTugasController;

Route::apiResource('tugas', TugasController::class);

Route::get('/nilai-tugas', [NilaiTugasController::class,'index']); // GET semua
Route::get('/nilai-tugas/{id}', [NilaiTugasController::class,'show']); // GET detail
Route::post('/nilai-tugas', [NilaiTugasController::class,'store']); // POST
Route::put('/nilai-tugas/{id}', [NilaiTugasController::class,'update']); // PUT
Route::delete('/nilai-tugas/{id}', [NilaiTugasController::class,'destroy']); // DELETE

Route::get('/nilai-tugas/siswa/{id}', [NilaiTugasController::class,'bySiswa']);
Route::get('/rata-tugas/{siswa}/{komponen}', [NilaiTugasController::class,'rataRata']);

Route::apiResource('presensi', PresensiSiswaController::class);


Route::apiResource('pengumuman', PengumumanController::class);

Route::apiResource('dokumen', DokumenAdministrasiController::class);
Route::post('/dokumen/update/{id}', [DokumenAdministrasiController::class, 'update']);

Route::apiResource('kelas', KelasController::class);
Route::get('/pegawai/guru-kelas', [PegawaiController::class, 'guruKelas']);
Route::get('/pegawai/guru-mapel', [PegawaiController::class, 'guruMapel']);



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('konten-umum', KontenUmumController::class);



Route::apiResource('jabatan', JabatanController::class);
Route::apiResource('pegawai', PegawaiController::class);

// login
Route::post('/login', [PegawaiController::class, 'login']);

Route::apiResource('mapel', MapelController::class);

Route::get('/tahun-ajaran/aktif', [TahunAjaranController::class, 'aktif']);
Route::apiResource('tahun-ajaran', TahunAjaranController::class);

Route::get('/kelas-saya/{id}', [KelasController::class, 'kelasSaya']);
Route::get('/kelas-sayaP/{id}', [KelasController::class, 'kelasSayaP']);

Route::apiResource('siswa', SiswaController::class);
Route::get('/siswa-kelas-saya/{id}', [SiswaController::class, 'siswaKelasSaya']);

Route::apiResource('jadwal', JadwalMengajarController::class);

Route::apiResource('presensi-guru', PresensiGuruController::class);
Route::apiResource('status-presensi', StatusPresensiController::class); 
Route::get('/presensi-saya/{id}', [PresensiGuruController::class, 'presensiSaya']);

Route::apiResource('komponen-penilaian', KomponenPenilaianController::class);
Route::get('/komponen-penilaian-guru/{id}', [KomponenPenilaianController::class, 'byGuru']);
Route::get('/siswa-by-guru/{id}', [SiswaController::class, 'byGuruJadwal']);

Route::get('/siswa-by-kelas/{id}', [SiswaController::class,'byKelas']);
Route::get('/jadwal-guru/{id}', [JadwalMengajarController::class, 'byGuru']);

Route::post('/nilai-siswa',[NilaiSiswaController::class,'store']);
Route::get('/nilai-siswa/{kelas}/{mapel}',[NilaiSiswaController::class,'index']);

Route::get('/tugas-by-mapel/{id_mapel}', [TugasController::class, 'byMapel']);

Route::get('/nilai-tugas/siswa/{siswa}/{mapel}', [NilaiTugasController::class,'bySiswa1']);
Route::get('/nilai-siswa/{kelas}/{mapel}', [NilaiSiswaController::class,'byKelasMapel']);

// Route::get('/nilai-tugas/siswa/{siswa}/{komponen}', [NilaiTugasController::class,'bySiswa']);
Route::get('/nilai-tugas/siswa/{id_siswa}/{id_mapel}', [NilaiTugasController::class,'bySiswa2']);

Route::get('/tugas-by-mapel/{id_mapel}', [TugasController::class, 'byMapel']);
Route::get('/tahun_ajaran/aktif1', [TahunAjaranController::class, 'aktif1']);

Route::get('/nilai-tugas-kelas/{kelas}/{mapel}', [NilaiTugasController::class,'nilaiTugasKelas']);
Route::get('/jadwal-guru-mapel/{id}', [JadwalMengajarController::class, 'byGuruMapel']);
