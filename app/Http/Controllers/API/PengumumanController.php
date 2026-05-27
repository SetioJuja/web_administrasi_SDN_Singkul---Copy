<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Log;

class PengumumanController extends Controller
{
    // ================= GET =================
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Pengumuman::orderBy('tanggal','desc')->get()
        ]);
    }

    // ================= INSERT =================
    public function store(Request $request)
    {
        try {

            Log::info('REQUEST PENGUMUMAN', $request->all());

            $validated = $request->validate([
                'judul' => 'required',
                'isi' => 'required',
                'tanggal' => 'required|date',
                'gambar' => 'nullable|image|max:2048'
            ]);

            // upload gambar
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $nama = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('upload'), $nama);
                $validated['gambar'] = $nama;
            }

            $data = Pengumuman::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil tambah',
                'data' => $data
            ]);

        } catch (\Exception $e) {

            Log::error('ERROR PENGUMUMAN', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan'
            ], 500);
        }
    }

    // ================= DETAIL =================
    public function show($id)
    {
        $data = Pengumuman::find($id);

        if(!$data){
            return response()->json([
                'success'=>false,
                'message'=>'Data tidak ditemukan'
            ],404);
        }

        return response()->json([
            'success'=>true,
            'data'=>$data
        ]);
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $data = Pengumuman::find($id);

        if(!$data){
            return response()->json([
                'success'=>false,
                'message'=>'Data tidak ditemukan'
            ],404);
        }

        try {

            $validated = $request->validate([
                'judul' => 'required',
                'isi' => 'required',
                'tanggal' => 'required|date',
                'gambar' => 'nullable|image|max:2048'
            ]);

            // upload gambar baru
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $nama = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('upload'), $nama);
                $validated['gambar'] = $nama;
            }

            $data->update($validated);

            return response()->json([
                'success'=>true,
                'message'=>'Berhasil update',
                'data'=>$data
            ]);

        } catch (\Exception $e){

            Log::error('ERROR UPDATE PENGUMUMAN', [
                'message'=>$e->getMessage()
            ]);

            return response()->json([
                'success'=>false,
                'message'=>'Gagal update'
            ],500);
        }
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        $data = Pengumuman::find($id);

        if(!$data){
            return response()->json([
                'success'=>false,
                'message'=>'Data tidak ditemukan'
            ],404);
        }

        // hapus file gambar jika ada
        if($data->gambar && file_exists(public_path('upload/'.$data->gambar))){
            unlink(public_path('upload/'.$data->gambar));
        }

        $data->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Berhasil hapus'
        ]);
    }
}
