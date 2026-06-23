<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Pegawai;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelas::with(['pegawai', 'tahunAjaran']);

        // Filter tabel berdasarkan tahun ajaran/semester yang dipilih di view
        if ($request->filled('id_tahun_ajaran')) {
            $query->where('id_tahun_ajaran', $request->id_tahun_ajaran);
        }

        $data = $query
            ->orderBy('id_tahun_ajaran', 'desc')
            ->orderBy('nama_kelas', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        Log::info('REQUEST TAMBAH KELAS', $request->all());

        $validator = Validator::make($request->all(), [
            'nama_kelas'       => 'required|integer|min:1|max:6',
            'id_guru'          => 'nullable|exists:pegawai,id_guru',
            'id_tahun_ajaran'  => 'required|exists:tahun_ajaran,id_tahun_ajaran'
        ], [
            'nama_kelas.required'      => 'Nama kelas wajib diisi',
            'nama_kelas.integer'       => 'Nama kelas harus berupa angka',
            'nama_kelas.min'           => 'Nama kelas minimal 1',
            'nama_kelas.max'           => 'Nama kelas maksimal 6',
            'id_guru.exists'           => 'Guru tidak ditemukan',
            'id_tahun_ajaran.required' => 'Tahun ajaran wajib dipilih',
            'id_tahun_ajaran.exists'   => 'Tahun ajaran tidak ditemukan'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        try {
            $tahunAjaran = TahunAjaran::find($validated['id_tahun_ajaran']);

            if (!$tahunAjaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tahun ajaran tidak ditemukan'
                ], 404);
            }

            $periode = $tahunAjaran->periode;

            /*
             * Cek kelas duplikat dalam satu periode tahun ajaran.
             * Contoh:
             * Kelas 1 di 2025/2026 Ganjil tidak boleh dibuat lagi
             * di 2025/2026 Genap.
             */
            $kelasDuplikat = Kelas::where('nama_kelas', $validated['nama_kelas'])
                ->whereHas('tahunAjaran', function ($q) use ($periode) {
                    $q->where('periode', $periode);
                })
                ->exists();

            if ($kelasDuplikat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas sudah ada pada tahun ajaran ' . $periode
                ], 400);
            }

            if (!empty($validated['id_guru'])) {
                $pegawai = Pegawai::with('jabatan')->find($validated['id_guru']);

                if (!$pegawai || !$pegawai->hasRole('Kelas')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pegawai bukan wali kelas'
                    ], 400);
                }

                /*
                 * Cek wali kelas duplikat dalam satu periode tahun ajaran.
                 * Guru yang sudah menjadi wali kelas di semester ganjil
                 * tidak boleh menjadi wali lagi di semester genap pada periode sama.
                 */
                $guruSudahWali = Kelas::where('id_guru', $validated['id_guru'])
                    ->whereHas('tahunAjaran', function ($q) use ($periode) {
                        $q->where('periode', $periode);
                    })
                    ->exists();

                if ($guruSudahWali) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Guru sudah menjadi wali kelas pada tahun ajaran ' . $periode
                    ], 400);
                }
            }

            $data = Kelas::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil tambah kelas',
                'data' => $data->load(['pegawai', 'tahunAjaran'])
            ], 201);

        } catch (\Exception $e) {
            Log::error('ERROR SIMPAN KELAS', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    public function show($id)
    {
        $data = Kelas::with(['pegawai', 'tahunAjaran'])->find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data kelas tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = Kelas::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data kelas tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_kelas'       => 'required|integer|min:1|max:6',
            'id_guru'          => 'nullable|exists:pegawai,id_guru',
            'id_tahun_ajaran'  => 'required|exists:tahun_ajaran,id_tahun_ajaran'
        ], [
            'nama_kelas.required'      => 'Nama kelas wajib diisi',
            'nama_kelas.integer'       => 'Nama kelas harus berupa angka',
            'nama_kelas.min'           => 'Nama kelas minimal 1',
            'nama_kelas.max'           => 'Nama kelas maksimal 6',
            'id_guru.exists'           => 'Guru tidak ditemukan',
            'id_tahun_ajaran.required' => 'Tahun ajaran wajib dipilih',
            'id_tahun_ajaran.exists'   => 'Tahun ajaran tidak ditemukan'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        try {
            $tahunAjaran = TahunAjaran::find($validated['id_tahun_ajaran']);

            if (!$tahunAjaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tahun ajaran tidak ditemukan'
                ], 404);
            }

            $periode = $tahunAjaran->periode;

            /*
             * Cek kelas duplikat dalam satu periode tahun ajaran.
             * Data yang sedang diedit tidak ikut dihitung.
             */
            $kelasDuplikat = Kelas::where('nama_kelas', $validated['nama_kelas'])
                ->where('id_kelas', '!=', $id)
                ->whereHas('tahunAjaran', function ($q) use ($periode) {
                    $q->where('periode', $periode);
                })
                ->exists();

            if ($kelasDuplikat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas sudah ada pada tahun ajaran ' . $periode
                ], 400);
            }

            if (!empty($validated['id_guru'])) {
                $pegawai = Pegawai::with('jabatan')->find($validated['id_guru']);

                if (!$pegawai || !$pegawai->hasRole('Kelas')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pegawai bukan wali kelas'
                    ], 400);
                }

                /*
                 * Cek guru sudah menjadi wali kelas lain dalam periode yang sama.
                 * Data yang sedang diedit tidak ikut dihitung.
                 */
                $guruSudahWali = Kelas::where('id_guru', $validated['id_guru'])
                    ->where('id_kelas', '!=', $id)
                    ->whereHas('tahunAjaran', function ($q) use ($periode) {
                        $q->where('periode', $periode);
                    })
                    ->exists();

                if ($guruSudahWali) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Guru sudah menjadi wali kelas lain pada tahun ajaran ' . $periode
                    ], 400);
                }
            }

            $data->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil update kelas',
                'data' => $data->load(['pegawai', 'tahunAjaran'])
            ]);

        } catch (\Exception $e) {
            Log::error('ERROR UPDATE KELAS', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
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
        $data = Kelas::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data kelas tidak ditemukan'
            ], 404);
        }

        $adaSiswa = Siswa::where('id_kelas', $id)->exists();

        if ($adaSiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak dapat dihapus karena masih memiliki siswa'
            ], 400);
        }

        try {
            $data->delete();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil hapus kelas'
            ]);

        } catch (\Exception $e) {
            Log::error('ERROR HAPUS KELAS', [
                'message'  => $e->getMessage(),
                'line'     => $e->getLine(),
                'file'     => $e->getFile(),
                'id_kelas' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    public function kelasSaya($id)
    {
        $kelas = Kelas::with(['tahunAjaran'])
            ->where('id_guru', $id)
            ->orderBy('id_tahun_ajaran', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $kelas
        ]);
    }

    public function kelasSayaP($id)
    {
        $siswa = Siswa::whereHas('kelas', function ($q) use ($id) {
                $q->where('id_guru', $id);
            })
            ->orderBy('nama_siswa', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $siswa
        ]);
    }
}