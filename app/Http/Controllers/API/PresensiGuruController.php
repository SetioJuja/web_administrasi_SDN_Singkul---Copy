<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PresensiGuru;
use Illuminate\Support\Facades\Log;

class PresensiGuruController extends Controller
{
    // ======================
    // GET ALL
    // ======================
    public function index()
    {
        Log::info('AMBIL DATA PRESENSI');

        return response()->json([
            'success' => true,
            'data' => PresensiGuru::with(['guru','status','tahunAjaran'])
                        ->orderBy('tanggal','desc')
                        ->get()
        ]);
    }

    // ======================
    // INSERT
    // ======================
    public function store(Request $request)
    {
        try {

            Log::info('REQUEST PRESENSI MASUK', $request->all());

            $validated = $request->validate([
                'id_guru' => 'required|exists:pegawai,id_guru',
                'tanggal' => 'required|date',
                'id_status' => 'required|exists:status_presensi,id_status',
                'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id_tahun_ajaran'
            ]);

            //  VALIDASI TIDAK BOLEH DOBEL
            $cek = PresensiGuru::where('id_guru', $validated['id_guru'])
                ->where('tanggal', $validated['tanggal'])
                ->exists();

            if($cek){
                return response()->json([
                    'success' => false,
                    'message' => 'Guru sudah presensi di tanggal ini'
                ], 400);
            }

            $data = PresensiGuru::create($validated);

            Log::info('PRESENSI BERHASIL', $data->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Presensi berhasil disimpan',
                'data' => $data
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

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

            Log::error('ERROR PRESENSI', [
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    // ======================
    // DETAIL
    // ======================
    public function show($id)
    {
        $data = PresensiGuru::with(['guru','status','tahunAjaran'])->find($id);

        if(!$data){
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ],404);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // ======================
    // UPDATE
    // ======================
    public function update(Request $request, $id)
    {
        $data = PresensiGuru::find($id);

        if(!$data){
            return response()->json([
                'success'=>false,
                'message'=>'Data tidak ditemukan'
            ],404);
        }

        try {

            $validated = $request->validate([
                'id_guru' => 'required|exists:pegawai,id_guru',
                'tanggal' => 'required|date',
                'id_status' => 'required|exists:status_presensi,id_status',
                'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id_tahun_ajaran'
            ]);

            $data->update($validated);

            Log::info('UPDATE PRESENSI', $data->toArray());

            return response()->json([
                'success'=>true,
                'message'=>'Berhasil update',
                'data'=>$data
            ]);

        } catch (\Exception $e){

            Log::error('ERROR UPDATE PRESENSI', [
                'message'=>$e->getMessage()
            ]);

            return response()->json([
                'success'=>false,
                'message'=>'Gagal update'
            ],500);
        }
    }

    // ======================
    // DELETE
    // ======================
    public function destroy($id)
    {
        $data = PresensiGuru::find($id);

        if(!$data){
            return response()->json([
                'success'=>false,
                'message'=>'Data tidak ditemukan'
            ],404);
        }

        $data->delete();

        Log::info('HAPUS PRESENSI', ['id'=>$id]);

        return response()->json([
            'success'=>true,
            'message'=>'Berhasil hapus'
        ]);
    }

    public function presensiSaya($id)
    {
        return response()->json([
            'success' => true,
            'data' => PresensiGuru::with(['status','tahunAjaran'])
                        ->where('id_guru', $id)
                        ->orderBy('tanggal','desc')
                        ->get()
        ]);
    }
}
