<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\KontenUmum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class KontenUmumController extends Controller
{
    public function index()
    {
        $data = KontenUmum::first();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data' => $data
        ]);
    }

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
            'total_siswa' => 'nullable|integer',

            'gambar_login' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gambar_beranda' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        // upload gambar login
        if ($request->hasFile('gambar_login')) {

            $file = $request->file('gambar_login');

            $filename = time() . '_login_' . $file->getClientOriginalName();

            $file->move(public_path('upload'), $filename);

            $validated['gambar_login'] = asset('upload/' . $filename);
        }

        // upload gambar beranda
        if ($request->hasFile('gambar_beranda')) {

            $file = $request->file('gambar_beranda');

            $filename = time() . '_beranda_' . $file->getClientOriginalName();

            $file->move(public_path('upload'), $filename);

            $validated['gambar_beranda'] = asset('upload/' . $filename);
        }

        $data = KontenUmum::first();

        if ($data) {

            // hapus gambar lama login
            if (
                isset($validated['gambar_login']) &&
                $data->gambar_login
            ) {

                $oldPath = public_path(
                    str_replace(asset('/'), '', $data->gambar_login)
                );

                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            // hapus gambar lama beranda
            if (
                isset($validated['gambar_beranda']) &&
                $data->gambar_beranda
            ) {

                $oldPath = public_path(
                    str_replace(asset('/'), '', $data->gambar_beranda)
                );

                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

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
            'total_siswa' => 'nullable|integer',

            'gambar_login' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gambar_beranda' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        // upload gambar login
        if ($request->hasFile('gambar_login')) {

            // hapus gambar lama
            if ($data->gambar_login) {

                $oldPath = public_path(
                    str_replace(asset('/'), '', $data->gambar_login)
                );

                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $file = $request->file('gambar_login');

            $filename = time() . '_login_' . $file->getClientOriginalName();

            $file->move(public_path('upload'), $filename);

            $validated['gambar_login'] = asset('upload/' . $filename);
        }

        // upload gambar beranda
        if ($request->hasFile('gambar_beranda')) {

            // hapus gambar lama
            if ($data->gambar_beranda) {

                $oldPath = public_path(
                    str_replace(asset('/'), '', $data->gambar_beranda)
                );

                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $file = $request->file('gambar_beranda');

            $filename = time() . '_beranda_' . $file->getClientOriginalName();

            $file->move(public_path('upload'), $filename);

            $validated['gambar_beranda'] = asset('upload/' . $filename);
        }

        $data->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        $data = KontenUmum::find($id);

        if (!$data) {

            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // hapus gambar login
        if ($data->gambar_login) {

            $oldPath = public_path(
                str_replace(asset('/'), '', $data->gambar_login)
            );

            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        // hapus gambar beranda
        if ($data->gambar_beranda) {

            $oldPath = public_path(
                str_replace(asset('/'), '', $data->gambar_beranda)
            );

            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}