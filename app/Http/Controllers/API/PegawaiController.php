<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{

    public function index()
    {
        $data = Pegawai::with('jabatan')->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_guru' => 'required|string',
            'nip' => 'nullable|unique:pegawai,nip',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'nullable',
            'no_telepon' => 'nullable',
            'email' => 'nullable|email',

            'golongan' => 'nullable|string',
            'pendidikan_tertinggi' => 'required|string',
            'status_kepegawaian' => 'required|string',

            'tanggal_masuk' => 'required|date',
            'password' => 'required|min:6',
            'username' => 'required|min:6',

            'jabatan' => 'required|array',
            'jabatan.*' => 'exists:jabatan,id_jabatan'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $pegawai = Pegawai::create($validated);

        $pegawai->jabatan()->sync($request->jabatan);

        return response()->json([
            'success' => true,
            'message' => 'Data pegawai berhasil ditambahkan',
            'data' => $pegawai->load('jabatan')
        ], 201);
    }

    public function show($id)
    {
        $data = Pegawai::with('jabatan')->find($id);

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
        $pegawai = Pegawai::find($id);

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'nama_guru' => 'required|string',
            'nip' => 'nullable|unique:pegawai,nip,' . $id . ',id_guru',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'nullable',
            'no_telepon' => 'nullable',
            'email' => 'nullable|email',

            // SESUAI MODEL
            'golongan' => 'nullable|string',
            'pendidikan_tertinggi' => 'required|string',
            'status_kepegawaian' => 'required|string',
            'username' => 'required|min:6',

            'tanggal_masuk' => 'required|date',
            'password' => 'nullable|min:6',

            // MULTI ROLE
            'jabatan' => 'required|array',
            'jabatan.*' => 'exists:jabatan,id_jabatan'
        ]);

        if ($request->password) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $pegawai->update($validated);

        $pegawai->jabatan()->sync($request->jabatan);

        return response()->json([
            'success' => true,
            'message' => 'Data pegawai berhasil diupdate',
            'data' => $pegawai->load('jabatan')
        ]);
    }

public function destroy($id)
{
    $pegawai = Pegawai::find($id);

    if (!$pegawai) {

        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    if ($pegawai->presensiGuru()->exists()) {

        return response()->json([
            'success' => false,
            'message' => 'Pegawai tidak dapat dihapus karena memiliki data presensi'
        ], 400);
    }

    if ($pegawai->jadwalMengajar()->exists()) {

        return response()->json([
            'success' => false,
            'message' => 'Pegawai tidak dapat dihapus karena memiliki jadwal mengajar'
        ], 400);
    }

    $pegawai->jabatan()->detach();

    $pegawai->delete();

    return response()->json([
        'success' => true,
        'message' => 'Data pegawai berhasil dihapus'
    ]);
}

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $pegawai = Pegawai::with('jabatan')
            ->where('username', $request->username)
            ->first();

        if (!$pegawai || !Hash::check($request->password, $pegawai->password)) {
            return response()->json([
                'success' => false,
                'message' => 'username atau password salah'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'id' => $pegawai->id_guru,
                'nama' => $pegawai->nama_guru,
                'username' => $pegawai->username,

                'roles' => $pegawai->jabatan->pluck('nama_jabatan'),
            ]
        ]);
    }

    public function guruKelas()
    {
        $data = Pegawai::whereHas('jabatan', function ($q) {
            $q->where('nama_jabatan', 'Kelas');
        })->with('jabatan')->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function guruMapel()
    {
        $data = Pegawai::whereHas('jabatan', function ($q) {
            $q->where('nama_jabatan', 'Mapel');
        })->with('jabatan')->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}