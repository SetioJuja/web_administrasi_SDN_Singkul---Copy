<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiSiswa extends Model
{
    protected $table = 'nilai_siswa';
    protected $primaryKey = 'id_nilai';
    public $timestamps = false;

    protected $fillable = [
        'id_siswa',
        'id_mapel',
        'id_kelas',
        'nilai_tugas',
        'nilai_uts',
        'nilai_uas',
        'total',
        'nilai_keterampilan',
        'deskripsi_pengetahuan',
        'deskripsi_keterampilan'
    ];

    public function siswa(){
        return $this->belongsTo(Siswa::class,'id_siswa','id_siswa');
    }

    public function mapel(){
        return $this->belongsTo(Mapel::class,'id_mapel','id_mapel');
    }

    public function kelas(){
        return $this->belongsTo(Kelas::class,'id_kelas','id_kelas');
    }
}