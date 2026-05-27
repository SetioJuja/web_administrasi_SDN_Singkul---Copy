<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;

class TahunAjaranController extends Controller
{
    /**
     * GET /api/tahun-ajaran
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => TahunAjaran::orderBy('id_tahun_ajaran', 'desc')->get()
        ]);
    }

    /**
     * POST /api/tahun-ajaran
     */
    public function store(Request $request)
{
    try {

        \Log::info('REQUEST MASUK:', $request->all());

        $validated = $request->validate([
            'periode' => 'required|string',
            'semester' => 'required|in:ganjil,genap',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        if ($validated['status'] === 'aktif') {
            DB::table('tahun_ajaran')->update(['status' => 'nonaktif']);
        }

        $data = TahunAjaran::create($validated);

        \Log::info('DATA BERHASIL DISIMPAN:', $data->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Tahun ajaran berhasil ditambahkan',
            'data' => $data
        ], 201);

    } catch (\Exception $e) {

        \Log::error('ERROR SIMPAN:', [
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}


    public function show($id)
    {
        $data = TahunAjaran::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * PUT /api/tahun-ajaran/{id}
     */
    public function update(Request $request, $id)
    {
        $data = TahunAjaran::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'periode' => 'required|string',
            'semester' => 'required|in:ganjil,genap',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        if ($validated['status'] === 'aktif') {
            DB::table('tahun_ajaran')->update(['status' => 'nonaktif']);
        }

        $data->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate',
            'data' => $data
        ]);
    }

    /**
     * DELETE /api/tahun-ajaran/{id}
     */
    public function destroy($id)
    {
        $data = TahunAjaran::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function aktif()
    {
        $data = TahunAjaran::where('status', 'aktif')->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

public function aktif1()
    {
        $data = TahunAjaran::where('status', 'aktif')->first(); 

        if(!$data){
            return response()->json([
                'success'=>false,
                'message'=>'Tidak ada tahun aktif'
            ],404);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}