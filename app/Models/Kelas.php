<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    public $timestamps = false;

    protected $fillable = [
        'nama_kelas',
        'total_siswa',
        'id_guru',            
        'id_tahun_ajaran'     
    ];

    // ================= RELASI =================

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_guru', 'id_guru');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }

    public function jadwal(){
         return $this->hasMany(JadwalMengajar::class,'id_kelas','id_kelas');
    }

    
}