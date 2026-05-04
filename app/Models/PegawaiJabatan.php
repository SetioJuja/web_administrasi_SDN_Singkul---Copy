<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PegawaiJabatan extends Model
{
    protected $table = 'pegawai_jabatan';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_guru',
        'id_jabatan'
    ];

    // Relasi ke Pegawai
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_guru', 'id_guru');
    }

    // Relasi ke Jabatan
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id_jabatan');
    }
}