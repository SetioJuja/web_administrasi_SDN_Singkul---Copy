<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusPresensi extends Model
{
    protected $table = 'status_presensi';
    protected $primaryKey = 'id_status';
    public $timestamps = false;

    protected $fillable = ['nama_status'];

    public function presensiGuru()
    {
        return $this->hasMany(PresensiGuru::class, 'id_status', 'id_status');
    }
}