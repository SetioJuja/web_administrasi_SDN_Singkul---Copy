<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\Log;

class SiswaController extends Controller
{
    /**
     * GET /api/siswa
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Siswa::with('kelas')->get()
        ]);
    }

    /**
     * POST /api/siswa
     */
    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'nama_siswa' => 'required',
                'nis' => 'required|unique:siswa,nis',
                'jenis_kelamin' => 'required|in:L,P',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required|date',
                'alamat' => 'nullable',
                'nama_ayah' => 'nullable',
                'nama_ibu' => 'nullable',
                'no_telepon' => 'nullable',
                'penghasilan' => 'nullable',
                'id_kelas' => 'required|exists:kelas,id_kelas'
            ]);

            $data = Siswa::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil ditambahkan',
                'data' => $data
            ]);

        } catch (\Exception $e) {

            Log::error('ERROR TAMBAH SISWA', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal tambah siswa'
            ], 500);
        }
    }

    /**
     * GET /api/siswa/{id}
     */
    public function show($id)
    {
        $data = Siswa::with('kelas')->find($id);

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
     * PUT /api/siswa/{id}
     */
    public function update(Request $request, $id)
    {
        $data = Siswa::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        try {

            $validated = $request->validate([
                'nama_siswa' => 'sometimes|required',
                'nis' => 'sometimes|required|unique:siswa,nis,' . $id . ',id_siswa',
                'jenis_kelamin' => 'sometimes|required|in:L,P',
                'tempat_lahir' => 'sometimes|required',
                'tanggal_lahir' => 'sometimes|required|date',
                'alamat' => 'nullable',
                'nama_ayah' => 'nullable',
                'nama_ibu' => 'nullable',
                'no_telepon' => 'nullable',
                'penghasilan' => 'nullable',
                'id_kelas' => 'sometimes|required|exists:kelas,id_kelas'
            ]);

            $data->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil update',
                'data' => $data
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE /api/siswa/{id}
     */
    public function destroy($id)
    {
        $data = Siswa::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil hapus'
        ]);
    }

    public function siswaKelasSaya($id)
{
    // ambil id_kelas dari guru
    $kelas = \App\Models\Kelas::where('id_guru', $id)->pluck('id_kelas');

    // ambil siswa dari kelas tersebut
    $siswa = \App\Models\Siswa::with('kelas')
                ->whereIn('id_kelas', $kelas)
                ->get();

    return response()->json([
        'success' => true,
        'data' => $siswa
    ]);
}

public function byGuruJadwal($id_guru)
{
    $data = \App\Models\Siswa::whereHas('kelas.jadwal', function($q) use ($id_guru){
        $q->where('id_guru', $id_guru);
    })->with('kelas')->get();

    return response()->json([
        'success'=>true,
        'data'=>$data
    ]);
}

public function byKelas($id)
{
    $data = \App\Models\Siswa::where('id_kelas',$id)->get();

    return response()->json([
        'success'=>true,
        'data'=>$data
    ]);
}
}
