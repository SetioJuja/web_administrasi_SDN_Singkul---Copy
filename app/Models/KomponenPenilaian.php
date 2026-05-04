<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomponenPenilaian extends Model
{
    protected $table = 'komponen_penilaian';
    protected $primaryKey = 'id_komponen';

    protected $fillable = [
        'id_mapel',
        'id_guru',
        'nama_komponen',
        'bobot'
    ];

    public $timestamps = false;

    // relasi
    public function mapel(){
        return $this->belongsTo(Mapel::class, 'id_mapel');
    }

    public function guru(){
        return $this->belongsTo(Pegawai::class, 'id_guru');
    }
}