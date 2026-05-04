<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KontenUmum;
use Illuminate\Http\Request;

class KontenUmumController extends Controller
{
    /**
     * GET /api/konten-umum
     * Ambil profil sekolah (1 data)
     */
    public function index()
    {
        $data = KontenUmum::first();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data' => $data
        ]);
    }

    /**
     * POST /api/konten-umum
     */
public function store(Request $request)
{
    $validated = $request->validate([
        'visi' => 'required|string',
        'misi' => 'required|string',
        'akreditasi' => 'nullable|string|max:10',
        'alamat' => 'nullable|string',
        'telepon' => 'nullable|string|max:20',
        'email' => 'nullable|email',
        'jam_operasional' => 'nullable|string',
        'total_guru' => 'nullable|integer',
        'total_siswa' => 'nullable|integer'
    ]);

    $data = KontenUmum::first();

    if ($data) {
        $data->update($validated);
        $message = 'Data berhasil diupdate';
    } else {
        $data = KontenUmum::create($validated);
        $message = 'Data berhasil ditambahkan';
    }

    return response()->json([
        'success' => true,
        'message' => $message,
        'data' => $data
    ]);
}

    /**
     * GET /api/konten-umum/{id}
     */
    public function show($id)
    {
        $data = KontenUmum::find($id);

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
     * PUT /api/konten-umum/{id}
     */
    public function update(Request $request, $id)
    {
        $data = KontenUmum::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'visi' => 'required|string',
            'misi' => 'required|string',
            'akreditasi' => 'nullable|string|max:10',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'jam_operasional' => 'nullable|string',
            'total_guru' => 'nullable|integer',
            'total_siswa' => 'nullable|integer'
        ]);

        $data->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate',
            'data' => $data
        ]);
    }

    /**
     * DELETE /api/konten-umum/{id}
     */
    public function destroy($id)
    {
        $data = KontenUmum::find($id);

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
}