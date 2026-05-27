<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontenUmum extends Model
{
    protected $table = 'konten_umum';
    protected $primaryKey = 'id_konten_umum';
    public $timestamps = false;

    protected $fillable = [
        'visi',
        'misi',
        'akreditasi',
        'alamat',
        'telepon',
        'email',
        'jam_operasional',
        'total_guru',
        'total_siswa',
        'gambar_login',
        'gambar_beranda'
    ];

}
