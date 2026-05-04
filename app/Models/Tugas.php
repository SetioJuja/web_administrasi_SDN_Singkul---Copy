<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';
    protected $primaryKey = 'id_tugas';
    public $timestamps = false;

    protected $fillable = [
        'id_komponen',
        'judul_tugas',
        'tanggal'
    ];

    // relasi
    public function komponen(){
        return $this->belongsTo(KomponenPenilaian::class,'id_komponen','id_komponen');
    }

    public function nilai(){
        return $this->hasMany(NilaiTugas::class,'id_tugas','id_tugas');
    }
}