<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\NilaiSiswa;

class NilaiSiswaController extends Controller
{
    // ================= SIMPAN NILAI =================
    public function store(Request $request)
    {
        $request->validate([
            'id_siswa'=>'required',
            'id_mapel'=>'required',
            'id_kelas'=>'required'
        ]);

        // 🔥 ambil AVG tugas
        $avg = DB::table('nilai_tugas')
            ->join('tugas','tugas.id_tugas','=','nilai_tugas.id_tugas')
            ->join('komponen_penilaian','komponen_penilaian.id_komponen','=','tugas.id_komponen')
            ->where('nilai_tugas.id_siswa',$request->id_siswa)
            ->where('komponen_penilaian.id_mapel',$request->id_mapel)
            ->avg('nilai_tugas.nilai');

        // 🔥 ambil bobot
        $komponen = DB::table('komponen_penilaian')
            ->where('id_mapel',$request->id_mapel)
            ->get();

        $bobot_tugas = $komponen->firstWhere(fn($k) => strtoupper($k->nama_komponen) == 'TUGAS')->bobot ?? 0;
        $bobot_uts   = $komponen->firstWhere(fn($k) => strtoupper($k->nama_komponen) == 'UTS')->bobot ?? 0;
        $bobot_uas   = $komponen->firstWhere(fn($k) => strtoupper($k->nama_komponen) == 'UAS')->bobot ?? 0;

        $uts = $request->nilai_uts ?? 0;
        $uas = $request->nilai_uas ?? 0;

        // 🔥 hitung total
        $total =
            ($avg * $bobot_tugas/100) +
            ($uts * $bobot_uts/100) +
            ($uas * $bobot_uas/100);

        // 🔥 simpan
        NilaiSiswa::updateOrCreate(
            [
                'id_siswa'=>$request->id_siswa,
                'id_mapel'=>$request->id_mapel
            ],
            [
                'id_kelas'=>$request->id_kelas,
                'nilai_tugas'=>$avg,
                'nilai_uts'=>$uts,
                'nilai_uas'=>$uas,
                'total'=>$total
            ]
        );

        return response()->json([
            'success'=>true,
            'total'=>$total
        ]);
    }

    // ================= LIST =================
    public function index($kelas,$mapel)
    {
        $data = NilaiSiswa::with('siswa')
            ->where('id_kelas',$kelas)
            ->where('id_mapel',$mapel)
            ->get();

        return response()->json([
            'success'=>true,
            'data'=>$data
        ]);
    }

    public function byKelasMapel($kelas,$mapel)
{
    $data = \App\Models\NilaiSiswa::where('id_kelas',$kelas)
        ->where('id_mapel',$mapel)
        ->get();

    return response()->json([
        'success'=>true,
        'data'=>$data
    ]);
}
}