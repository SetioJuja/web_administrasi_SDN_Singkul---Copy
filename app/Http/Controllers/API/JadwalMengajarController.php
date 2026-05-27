<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalMengajar;
use Illuminate\Support\Facades\Log;

class JadwalMengajarController extends Controller
{
    public function index()
    {
        Log::info('AMBIL DATA JADWAL');

        return response()->json([
            'success' => true,
            'data' => JadwalMengajar::with(['guru','kelas','mapel','tahunAjaran'])->get()
        ]);
    }

    public function store(Request $request)
    {
        try {

            // LOG REQUEST MASUK
            Log::info('REQUEST TAMBAH JADWAL', $request->all());

            // VALIDASI
            $validated = $request->validate([
                'hari' => 'required',
                'jam_mulai' => 'required',
                'jam_selesai' => 'required',
                'id_guru' => 'required|exists:pegawai,id_guru',
                'id_kelas' => 'required|exists:kelas,id_kelas',
                'id_mapel' => 'required|exists:mapel,id_mapel',
                'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id_tahun_ajaran'
            ]);

            //  LOG VALIDASI BERHASIL
            Log::info('VALIDASI BERHASIL', $validated);

            //  SIMPAN
            $data = JadwalMengajar::create($validated);

            //  LOG SUCCESS
            Log::info('JADWAL BERHASIL DISIMPAN', $data->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Berhasil tambah jadwal',
                'data' => $data
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            //  LOG VALIDASI GAGAL
            Log::error('VALIDASI GAGAL', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {

            //  LOG ERROR
            Log::error('ERROR SIMPAN JADWAL', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {

            Log::info('HAPUS JADWAL', ['id' => $id]);

            $data = JadwalMengajar::find($id);

            if (!$data) {
                Log::warning('DATA TIDAK DITEMUKAN', ['id' => $id]);

                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $data->delete();

            Log::info('JADWAL BERHASIL DIHAPUS', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil hapus'
            ]);

        } catch (\Exception $e) {

            Log::error('ERROR HAPUS JADWAL', [
                'message' => $e->getMessage(),
                'id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal hapus data'
            ], 500);
        }
    }

    public function byGuru($id)
        {
            $data = \App\Models\JadwalMengajar::with(['kelas','mapel'])
                ->where('id_guru', $id)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

// public function byGuruMapel($id_guru)
// {
//     $data = \App\Models\JadwalMengajar::with('mapel')
//         ->where('id_guru', $id_guru)
//         ->get()
//         ->pluck('mapel')        
//         ->unique('id_mapel')    
//         ->values();

//     return response()->json([
//         'success' => true,
//         'data' => $data
//     ]);
// }

public function byGuruMapel($id_guru)
{
    $data = \App\Models\JadwalMengajar::with([
            'kelas',
            'mapel'
        ])
        ->where('id_guru', $id_guru)
        ->get()
        ->unique(function ($item) {

            return
                $item->id_kelas .
                '-' .
                $item->id_mapel;
        })
        ->values();

    return response()->json([

        'success' => true,

        'data' => $data
    ]);
}
}
