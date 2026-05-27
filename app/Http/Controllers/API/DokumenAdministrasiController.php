<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DokumenAdministrasi;
use Illuminate\Support\Facades\Log;

class DokumenAdministrasiController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => DokumenAdministrasi::with('tahunAjaran')->get()
        ]);
    }

    public function show($id)
    {
        $data = DokumenAdministrasi::find($id);

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

    public function store(Request $request)
    {
        Log::info('STORE DOKUMEN', $request->all());

        $validated = $request->validate([
            'judul_dokumen' => 'required|string',
            'gambar' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx',
            'id_tahun_ajaran' => 'required',
            'keterangan' => 'nullable'
        ]);

        $namaFile = null;

        if($request->hasFile('gambar')){
            $file = $request->file('gambar');
            $namaFile = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads'), $namaFile);
        }

        $data = DokumenAdministrasi::create([
            'judul_dokumen'=>$validated['judul_dokumen'],
            'gambar'=>$namaFile,
            'tanggal_upload'=>now(),
            'keterangan'=>$validated['keterangan'] ?? null,
            'id_tahun_ajaran'=>$validated['id_tahun_ajaran']
        ]);

        return response()->json(['success'=>true,'data'=>$data]);
    }

    public function update(Request $request, $id)
    {
        Log::info('=== MASUK UPDATE ===', $request->all());

        $data = DokumenAdministrasi::find($id);

        if(!$data){
            return response()->json([
                'success'=>false,
                'message'=>'Data tidak ditemukan'
            ],404);
        }

        $validated = $request->validate([
            'judul_dokumen' => 'required|string',
            'gambar' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx',
            'id_tahun_ajaran' => 'required',
            'keterangan' => 'nullable'
        ]);

        $updateData = [
            'judul_dokumen'=>$validated['judul_dokumen'],
            'keterangan'=>$validated['keterangan'] ?? null,
            'id_tahun_ajaran'=>$validated['id_tahun_ajaran']
        ];

        if($request->hasFile('gambar')){

            if($data->gambar){
                $old = public_path('uploads/'.$data->gambar);
                if(file_exists($old)) unlink($old);
            }

            $file = $request->file('gambar');
            $namaFile = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads'), $namaFile);

            $updateData['gambar'] = $namaFile;
        }

        $data->update($updateData);

        return response()->json([
            'success'=>true,
            'message'=>'Update berhasil'
        ]);
    }

    public function destroy($id)
    {
        $data = DokumenAdministrasi::find($id);

        if(!$data) return;

        if($data->gambar){
            $path = public_path('uploads/'.$data->gambar);
            if(file_exists($path)) unlink($path);
        }

        $data->delete();

        return response()->json(['success'=>true]);
    }
}