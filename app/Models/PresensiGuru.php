<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresensiGuru extends Model
{
    protected $table = 'presensi_guru';
    protected $primaryKey = 'id_presensi_guru';
    public $timestamps = false;

    protected $fillable = [
        'id_guru',
        'tanggal',
        'id_status',
        'id_tahun_ajaran'
    ];

    public function guru()
    {
        return $this->belongsTo(Pegawai::class, 'id_guru', 'id_guru');
    }

    public function status()
    {
        return $this->belongsTo(StatusPresensi::class, 'id_status', 'id_status');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }
}