<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StatusPresensi;
use Illuminate\Support\Facades\Log;

class StatusPresensiController extends Controller
{
    // ======================
    // GET ALL
    // ======================
    public function index()
    {
        Log::info('AMBIL DATA STATUS PRESENSI');

        return response()->json([
            'success' => true,
            'data' => StatusPresensi::all()
        ]);
    }

    // ======================
    // INSERT
    // ======================
    public function store(Request $request)
    {
        try {

            Log::info('REQUEST TAMBAH STATUS', $request->all());

            $validated = $request->validate([
                'nama_status' => 'required|string|unique:status_presensi,nama_status'
            ]);

            $data = StatusPresensi::create($validated);

            Log::info('STATUS BERHASIL DITAMBAH', $data->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil ditambahkan',
                'data' => $data
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {

            Log::error('VALIDASI GAGAL STATUS', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {

            Log::error('ERROR TAMBAH STATUS', [
                'message' => $e->getMessage()
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
        $data = StatusPresensi::find($id);

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

    // ======================
    // UPDATE
    // ======================
    public function update(Request $request, $id)
    {
        $data = StatusPresensi::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        try {

            $validated = $request->validate([
                'nama_status' => 'required|string|unique:status_presensi,nama_status,' . $id . ',id_status'
            ]);

            $data->update($validated);

            Log::info('UPDATE STATUS PRESENSI', $data->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diupdate',
                'data' => $data
            ]);

        } catch (\Exception $e) {

            Log::error('ERROR UPDATE STATUS', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal update data'
            ], 500);
        }
    }

    // ======================
    // DELETE
    // ======================
    public function destroy($id)
    {
        $data = StatusPresensi::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // 🔥 CEK DIGUNAKAN DI PRESENSI
        if ($data->presensiGuru()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Status masih digunakan oleh data presensi'
            ], 400);
        }

        $data->delete();

        Log::info('HAPUS STATUS PRESENSI', ['id' => $id]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil dihapus'
        ]);
    }
}
