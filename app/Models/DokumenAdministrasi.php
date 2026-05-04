<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenAdministrasi extends Model
{
    protected $table = 'dokumen_administrasi';
    protected $primaryKey = 'id_dokumen';
    public $timestamps = false;

    protected $fillable = [
        'judul_dokumen',
        'gambar',            
        'tanggal_upload',
        'keterangan',
        'id_tahun_ajaran'   
    ];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }
}