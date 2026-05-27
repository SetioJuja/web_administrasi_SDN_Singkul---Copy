<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jabatan;

class JabatanController extends Controller
{
    /**
     * GET /api/jabatan
     * Ambil semua jabatan + jumlah pegawai
     */
    public function index()
    {
        $data = Jabatan::withCount('pegawai')->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * POST /api/jabatan
     * Tambah jabatan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jabatan' => 'required|string|unique:jabatan,nama_jabatan'
        ]);

        $data = Jabatan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Jabatan berhasil ditambahkan',
            'data' => $data
        ], 201);
    }

    /**
     * GET /api/jabatan/{id}
     * Detail jabatan + daftar pegawai
     */
    public function show($id)
    {
        $data = Jabatan::with(['pegawai' => function($q){
            $q->select('pegawai.id_guru','pegawai.nama_guru');
        }])->find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Jabatan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * PUT /api/jabatan/{id}
     * Update nama jabatan
     */
    public function update(Request $request, $id)
    {
        $data = Jabatan::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Jabatan tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'nama_jabatan' => 'required|string|unique:jabatan,nama_jabatan,' . $id . ',id_jabatan'
        ]);

        $data->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Jabatan berhasil diupdate',
            'data' => $data
        ]);
    }

    /**
     * DELETE /api/jabatan/{id}
     * Hapus jabatan (jika tidak dipakai)
     */
    public function destroy($id)
    {
        $data = Jabatan::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Jabatan tidak ditemukan'
            ], 404);
        }

        // CEK RELASI PIVOT
        if ($data->pegawai()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Jabatan masih digunakan oleh pegawai'
            ], 400);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jabatan berhasil dihapus'
        ]);
    }
}
