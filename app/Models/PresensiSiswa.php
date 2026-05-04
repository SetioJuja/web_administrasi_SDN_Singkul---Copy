<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresensiSiswa extends Model
{
    protected $table = 'presensi_siswa';
    protected $primaryKey = 'id_presensi_siswa';

    protected $fillable = [
        'id_siswa',
        'tanggal',
        'id_status',
        'id_tahun_ajaran'
    ];

    public $timestamps = false;

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    // Relasi ke status (hadir, izin, sakit)
    public function status()
    {
        return $this->belongsTo(StatusPresensi::class, 'id_status');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }
}