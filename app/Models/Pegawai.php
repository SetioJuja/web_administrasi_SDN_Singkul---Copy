<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pegawai extends Authenticatable
{
    protected $table = 'pegawai';
    protected $primaryKey = 'id_guru';
    public $timestamps = false;

    protected $fillable = [
        'nama_guru',
        'nip',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telepon',
        'email',
        'tanggal_masuk',
        'password',
        'username',
        'golongan',
        'pendidikan_tertinggi',
        'status_kepegawaian'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date'
    ];

    /**
     * Relasi MANY TO MANY ke jabatan
     */
    public function jabatan()
    {
        return $this->belongsToMany(
            Jabatan::class,
            'pegawai_jabatan',
            'id_guru',
            'id_jabatan'
        );
    }

    /**
     * Helper cek role
     */
    public function hasRole($role)
    {
        return $this->jabatan()->where('nama_jabatan', $role)->exists();
    }

        public function presensiGuru()
    {
        return $this->hasMany(PresensiGuru::class, 'id_guru');
    }

    public function jadwalMengajar()
    {
        return $this->hasMany(JadwalMengajar::class, 'id_guru');
    }
}
