<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PresensiSiswa;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Log;

class PresensiSiswaController extends Controller
{
    // ================= GET SEMUA =================
    public function index()
{
    $data = PresensiSiswa::with(['siswa', 'status', 'tahunAjaran'])
        ->get()
        ->map(function($item){
            return [
                'id_presensi_siswa' => $item->id_presensi_siswa,

                //  INI YANG PALING PENTING
                'id_siswa' => $item->id_siswa,

                'tanggal' => $item->tanggal,

                //  ubah jadi ID 
                'id_status' => $item->id_status,

                // optional (boleh tetap ada)
                'nama_siswa' => $item->siswa->nama_siswa ?? null,
                'status' => $item->status->nama_status ?? null,
                'periode' => $item->tahunAjaran->periode ?? null,
                'semester' => $item->tahunAjaran->semester ?? null
            ];
        });

    return response()->json([
        'success' => true,
        'message' => 'Data presensi siswa',
        'data' => $data
    ]);
}

    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required',
            'tanggal' => 'required|date',
            'id_status' => 'required'
        ]);

        //  ambil tahun ajaran aktif
        $tahun = TahunAjaran::where('status', 'aktif')->first();

        if(!$tahun){
            return response()->json([
                'success'=>false,
                'message'=>'Tahun ajaran aktif tidak ditemukan'
            ],400);
        }

        Log::info('STORE PRESENSI', [
            'siswa' => $request->id_siswa,
            'tanggal' => $request->tanggal,
            'tahun' => $tahun->id_tahun_ajaran
        ]);

        //  cek duplikat (lebih ketat)
        $cek = PresensiSiswa::where('id_siswa', $request->id_siswa)
            ->where('tanggal', $request->tanggal)
            ->where('id_tahun_ajaran', $tahun->id_tahun_ajaran)
            ->exists();

        if ($cek) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa sudah presensi di tanggal tersebut'
            ], 400);
        }

        $data = PresensiSiswa::create([
            'id_siswa' => $request->id_siswa,
            'tanggal' => $request->tanggal,
            'id_status' => $request->id_status,
            'id_tahun_ajaran' => $tahun->id_tahun_ajaran
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil ditambahkan',
            'data' => $data
        ], 201);
    }

    // ================= SHOW =================
    public function show($id)
    {
        $data = PresensiSiswa::with(['siswa', 'status', 'tahunAjaran'])
            ->find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id_presensi_siswa' => $data->id_presensi_siswa,
                'nama_siswa' => $data->siswa->nama_siswa ?? null,
                'tanggal' => $data->tanggal,
                'status' => $data->status->nama_status ?? null,
                'periode' => $data->tahunAjaran->periode ?? null,
                'semester' => $data->tahunAjaran->semester ?? null
            ]
        ]);
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $data = PresensiSiswa::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'id_siswa' => 'required',
            'tanggal' => 'required|date',
            'id_status' => 'required'
        ]);

        //  ambil tahun ajaran aktif
        $tahun = TahunAjaran::where('status', 'aktif')->first();

        if(!$tahun){
            return response()->json([
                'success'=>false,
                'message'=>'Tahun ajaran aktif tidak ditemukan'
            ],400);
        }

        //  cek duplikat (kecuali dirinya sendiri)
        $cek = PresensiSiswa::where('id_siswa', $request->id_siswa)
            ->where('tanggal', $request->tanggal)
            ->where('id_tahun_ajaran', $tahun->id_tahun_ajaran)
            ->where('id_presensi_siswa', '!=', $id)
            ->exists();

        if ($cek) {
            return response()->json([
                'success' => false,
                'message' => 'Data presensi duplikat'
            ], 400);
        }

        $data->update([
            'id_siswa' => $request->id_siswa,
            'tanggal' => $request->tanggal,
            'id_status' => $request->id_status,
            'id_tahun_ajaran' => $tahun->id_tahun_ajaran
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil diupdate',
            'data' => $data
        ]);
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        $data = PresensiSiswa::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil dihapus'
        ]);
    }
}