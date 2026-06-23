<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mapel;

class MapelController extends Controller
{

    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Mapel::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_mapel' => 'required|string',
            'kode_mapel' => 'required|string|unique:mapel,kode_mapel'
        ]);

        $data = Mapel::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Mapel berhasil ditambahkan',
            'data' => $data
        ], 201);
    }

    public function show($id)
    {
        $data = Mapel::find($id);

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

    public function update(Request $request, $id)
    {
        $data = Mapel::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'nama_mapel' => 'required|string',
            'kode_mapel' => 'required|unique:mapel,kode_mapel,'.$id.',id_mapel'
        ]);

        $data->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Mapel berhasil diupdate',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        $data = Mapel::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mapel berhasil dihapus'
        ]);
    }

    public function byGuru($id_guru)
    {
        $data = \App\Models\JadwalMengajar::with(['mapel','kelas'])
            ->where('id_guru', $id_guru)
            ->get();

        return response()->json([
            'data'=>$data
        ]);
    }
}