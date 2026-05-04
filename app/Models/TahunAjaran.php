<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';

    protected $primaryKey = 'id_tahun_ajaran'; // 🔥 WAJIB

    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'periode',
        'semester',
        'status'
    ];
}