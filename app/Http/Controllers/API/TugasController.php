<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\KomponenPenilaian;

class TugasController extends Controller
{
    // ================= GET SEMUA =================
    public function index(Request $request)
    {
        //  ambil dari request (sementara)
        $id_guru = $request->id_guru;

        $data = Tugas::with('komponen.mapel')
            ->whereHas('komponen', function($q) use ($id_guru){
                $q->where('id_guru', $id_guru);
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // ================= POST =================
public function store(Request $request)
{
    $validated = $request->validate([
        'id_komponen' => 'required',
        'id_kelas' => 'required', // 
        'judul_tugas' => 'required|string',
        'tanggal' => 'required|date'
    ]);

    $komponen = KomponenPenilaian::find($validated['id_komponen']);

    if(!$komponen){
        return response()->json([
            'success'=>false,
            'message'=>'Komponen tidak ditemukan'
        ],404);
    }

    if($request->id_guru && $komponen->id_guru != $request->id_guru){
        return response()->json([
            'success'=>false,
            'message'=>'Tidak boleh akses komponen ini'
        ],403);
    }

    $data = Tugas::create([
        'id_komponen' => $validated['id_komponen'],
        'id_kelas' => $validated['id_kelas'], // 
        'judul_tugas' => $validated['judul_tugas'],
        'tanggal' => $validated['tanggal']
    ]);

    return response()->json([
        'success'=>true,
        'data'=>$data
    ]);
}

    // ================= GET DETAIL =================
    public function show($id)
    {
        $data = Tugas::with('komponen.mapel')->find($id);

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
        $data = Tugas::find($id);

        if(!$data){
            return response()->json([
                'success'=>false,
                'message'=>'Data tidak ditemukan'
            ],404);
        }

        $validated = $request->validate([
            'judul_tugas' => 'required|string',
            'tanggal' => 'required|date'
        ]);

        $data->update($validated);

        return response()->json([
            'success'=>true,
            'message'=>'Update berhasil'
        ]);
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        $data = Tugas::find($id);

        if(!$data){
            return response()->json([
                'success'=>false,
                'message'=>'Data tidak ditemukan'
            ],404);
        }

        $data->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Berhasil dihapus'
        ]);
    }

    public function byMapel($id_mapel, Request $request)
    {
        $id_guru = $request->id_guru;
        $id_kelas = $request->id_kelas; // 

        \Log::info('=== BY MAPEL ===', [
            'id_mapel'=>$id_mapel,
            'id_guru'=>$id_guru,
            'id_kelas'=>$id_kelas
        ]);

        $query = Tugas::with('komponen.mapel')
            ->where('id_kelas', $id_kelas) // 
            ->whereHas('komponen', function($q) use ($id_mapel, $id_guru){

                $q->where('id_mapel', $id_mapel);

                if(!empty($id_guru)){
                    $q->where('id_guru', $id_guru);
                }
            });

        $data = $query->get();

        return response()->json([
            'success'=>true,
            'data'=>$data
        ]);
    }
}