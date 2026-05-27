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
            'id_siswa' => 'required',
            'id_mapel' => 'required',
            'id_kelas' => 'required'
        ]);

        // ================= AVG TUGAS =================
        $avg = DB::table('nilai_tugas')
            ->join('tugas','tugas.id_tugas','=','nilai_tugas.id_tugas')
            ->join('komponen_penilaian','komponen_penilaian.id_komponen','=','tugas.id_komponen')
            ->where('nilai_tugas.id_siswa',$request->id_siswa)
            ->where('komponen_penilaian.id_mapel',$request->id_mapel)
            ->avg('nilai_tugas.nilai');

        //  jika tidak ada tugas
        $avg = $avg ?? 0;

        // ================= BOBOT =================
        $komponen = DB::table('komponen_penilaian')
            ->where('id_mapel',$request->id_mapel)
            ->get();

        $bobot_tugas = $komponen
            ->firstWhere(fn($k) => strtoupper($k->nama_komponen) == 'TUGAS')
            ->bobot ?? 0;

        $bobot_uts = $komponen
            ->firstWhere(fn($k) => strtoupper($k->nama_komponen) == 'UTS')
            ->bobot ?? 0;

        $bobot_uas = $komponen
            ->firstWhere(fn($k) => strtoupper($k->nama_komponen) == 'UAS')
            ->bobot ?? 0;

        // ================= NILAI =================
        $uts = $request->nilai_uts ?? 0;
        $uas = $request->nilai_uas ?? 0;

        //  pastikan numeric
        $uts = is_numeric($uts) ? $uts : 0;
        $uas = is_numeric($uas) ? $uas : 0;

        // ================= TOTAL =================
        //  tetap dihitung walaupun tugas kosong
        $total =
            ($avg * ($bobot_tugas / 100)) +
            ($uts * ($bobot_uts / 100)) +
            ($uas * ($bobot_uas / 100));

        // ================= INPUT MANUAL =================
        //  TIDAK IKUT HITUNGAN TOTAL
        $nilai_keterampilan     = $request->nilai_keterampilan;
        $deskripsi_pengetahuan  = $request->deskripsi_pengetahuan;
        $deskripsi_keterampilan = $request->deskripsi_keterampilan;

        // ================= SIMPAN =================
        NilaiSiswa::updateOrCreate(

            [
                'id_siswa' => $request->id_siswa,
                'id_mapel' => $request->id_mapel
            ],

            [
                'id_kelas' => $request->id_kelas,

                'nilai_tugas' => $avg,
                'nilai_uts'   => $uts,
                'nilai_uas'   => $uas,

                //  hasil akhir
                'total'       => round($total,2),

                //  manual
                'nilai_keterampilan'    => $nilai_keterampilan,
                'deskripsi_pengetahuan' => $deskripsi_pengetahuan,
                'deskripsi_keterampilan'=> $deskripsi_keterampilan
            ]
        );

        return response()->json([
            'success' => true,
            'total'   => round($total,2)
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


    // ================= BY KELAS MAPEL =================
    public function byKelasMapel($kelas,$mapel)
    {
        $data = NilaiSiswa::where('id_kelas',$kelas)
            ->where('id_mapel',$mapel)
            ->get();

        return response()->json([
            'success'=>true,
            'data'=>$data
        ]);
    }
}