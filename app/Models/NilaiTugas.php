<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiTugas extends Model
{
    protected $table = 'nilai_tugas';
    protected $primaryKey = 'id_nilai';
    public $timestamps = false;

    protected $fillable = [
        'id_tugas',
        'id_siswa',
        'nilai'
    ];

    // relasi
    public function tugas(){
        return $this->belongsTo(Tugas::class,'id_tugas','id_tugas');
    }

    public function siswa(){
        return $this->belongsTo(Siswa::class,'id_siswa','id_siswa');
    }
}