<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatan';
    protected $primaryKey = 'id_jabatan';
    public $timestamps = false;

    protected $fillable = [
        'nama_jabatan'
    ];

    /**
     * Relasi MANY TO MANY ke pegawai
     */
    public function pegawai()
    {
        return $this->belongsToMany(
            Pegawai::class,
            'pegawai_jabatan',
            'id_jabatan',
            'id_guru'
        );
    }
}
