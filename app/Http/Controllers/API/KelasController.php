<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Log;

class KelasController extends Controller
{
    /**
     * GET /api/kelas
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Kelas::with(['pegawai','tahunAjaran'])->get()
        ]);
    }

    /**
     * POST /api/kelas
     */
public function store(Request $request)
{
    Log::info('REQUEST TAMBAH KELAS', $request->all());

    try {

        $validated = $request->validate([
            'nama_kelas'       => 'required|integer',
            'total_siswa'      => 'required|integer',
            'id_guru'          => 'nullable|exists:pegawai,id_guru',
            'id_tahun_ajaran'  => 'required|exists:tahun_ajaran,id_tahun_ajaran'
        ]);

        Log::info('VALIDASI OK', $validated);

        // =====================================================
        // CEK GURU HANYA JIKA ADA ID GURU
        // =====================================================
        if (!empty($validated['id_guru'])) {

            $pegawai = Pegawai::with('jabatan')
                ->find($validated['id_guru']);

            if (!$pegawai || !$pegawai->hasRole('Kelas')) {

                Log::warning('VALIDASI GAGAL: BUKAN GURU KELAS', [
                    'id_guru' => $validated['id_guru']
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Pegawai bukan wali kelas'
                ], 400);
            }

            // =====================================================
            // CEK SUDAH JADI WALI
            // =====================================================
            if (
                Kelas::where('id_guru', $validated['id_guru'])
                ->exists()
            ) {

                return response()->json([
                    'success' => false,
                    'message' => 'Guru sudah menjadi wali kelas'
                ], 400);
            }
        }

        // =====================================================
        // SIMPAN
        // =====================================================
        $data = Kelas::create($validated);

        Log::info('BERHASIL SIMPAN KELAS', [
            'id_kelas' => $data->id_kelas,
            'data' => $data
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil tambah',
            'data' => $data
        ]);

    } catch (\Exception $e) {

        Log::error('ERROR SIMPAN KELAS', [
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

    /**
     * GET /api/kelas/{id}
     */
    public function show($id)
    {
        return response()->json([
            'success' => true,
            'data' => Kelas::find($id)
        ]);
    }

    /**
     * PUT /api/kelas/{id}
     */
    public function update(Request $request, $id)
{
    $data = Kelas::find($id);

    if (!$data) {
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    $validated = $request->validate([
        'nama_kelas'       => 'required|integer',
        'total_siswa'      => 'required|integer',
        'id_guru'          => 'nullable|exists:pegawai,id_guru',
        'id_tahun_ajaran'  => 'required|exists:tahun_ajaran,id_tahun_ajaran'
    ]);

    // Cek role hanya jika id_guru diisi
    if (!empty($validated['id_guru'])) {

        $pegawai = Pegawai::with('jabatan')->find($validated['id_guru']);

        if (!$pegawai || !$pegawai->hasRole('Kelas')) {
            return response()->json([
                'success' => false,
                'message' => 'Pegawai bukan wali kelas'
            ], 400);
        }

        // Cek duplikat, kecuali dirinya sendiri
        if (Kelas::where('id_guru', $validated['id_guru'])
            ->where('id_kelas', '!=', $id)
            ->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Guru sudah menjadi wali kelas lain'
            ], 400);
        }
    }

    $data->update($validated);

    return response()->json([
        'success' => true,
        'message' => 'Berhasil update',
        'data'    => $data->load(['pegawai', 'tahunAjaran'])
    ]);
}

    /**
     * DELETE /api/kelas/{id}
     */
    public function destroy($id)
    {
        $data = Kelas::find($id);

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

    public function kelasSaya($id)
{
    $kelas = Kelas::with(['tahunAjaran'])
        ->where('id_guru', $id)
        ->get();

    return response()->json([
        'success' => true,
        'data' => $kelas
    ]);
}

public function kelasSayaP($id)
{
    $siswa = \App\Models\Siswa::whereHas('kelas', function($q) use ($id){
        $q->where('id_guru', $id);
    })->get();

    \Log::info('DATA SISWA:', $siswa->toArray());

    return response()->json([
        'success' => true,
        'data' => $siswa
    ]);
}
}
