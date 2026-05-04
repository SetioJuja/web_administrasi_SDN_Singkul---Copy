<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NilaiTugas;
use Illuminate\Support\Facades\DB;

class NilaiTugasController extends Controller
{
    // ================= GET ALL =================
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => NilaiTugas::with(['tugas','siswa'])->get()
        ]);
    }

    // ================= GET DETAIL =================
    public function show($id)
    {
        $data = NilaiTugas::with(['tugas','siswa'])->find($id);

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

    // ================= POST =================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_tugas' => 'required',
            'id_siswa' => 'required',
            'nilai' => 'required|numeric|min:0|max:100'
        ]);

        // 🔥 update kalau sudah ada (tidak duplicate)
        $data = NilaiTugas::updateOrCreate(
            [
                'id_tugas' => $validated['id_tugas'],
                'id_siswa' => $validated['id_siswa']
            ],
            [
                'nilai' => $validated['nilai']
            ]
        );

        return response()->json([
            'success'=>true,
            'data'=>$data
        ]);
    }

    // ================= PUT =================
    public function update(Request $request, $id)
    {
        $data = NilaiTugas::find($id);

        if(!$data){
            return response()->json([
                'success'=>false,
                'message'=>'Data tidak ditemukan'
            ],404);
        }

        $validated = $request->validate([
            'nilai' => 'required|numeric|min:0|max:100'
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
        $data = NilaiTugas::find($id);

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

    // ================= NILAI PER SISWA =================
    public function bySiswa($id_siswa)
    {
        $data = NilaiTugas::with('tugas')
            ->where('id_siswa',$id_siswa)
            ->get();

        return response()->json([
            'success'=>true,
            'data'=>$data
        ]);
    }

    // ================= RATA-RATA =================
    public function rataRata($id_siswa, $id_komponen)
    {
        $rata = DB::table('nilai_tugas')
            ->join('tugas','tugas.id_tugas','=','nilai_tugas.id_tugas')
            ->where('nilai_tugas.id_siswa',$id_siswa)
            ->where('tugas.id_komponen',$id_komponen)
            ->avg('nilai_tugas.nilai');

        return response()->json([
            'success'=>true,
            'rata_rata'=> round($rata ?? 0,2)
        ]);
    }

    public function bySiswa1($id_siswa, $id_mapel)
        {
            $data = NilaiTugas::with('tugas.komponen')
                ->where('id_siswa',$id_siswa)
                ->whereHas('tugas.komponen', function($q) use ($id_mapel){
                    $q->where('id_mapel',$id_mapel);
                })
                ->get();

            return response()->json([
                'success'=>true,
                'data'=>$data
            ]);
        }

       public function bySiswa2($id_siswa, $id_mapel)
        {
            \Log::info('AMBIL NILAI', [
                'siswa'=>$id_siswa,
                'mapel'=>$id_mapel
            ]);

            $data = \App\Models\NilaiTugas::with('tugas')
                ->where('id_siswa',$id_siswa)
                ->whereHas('tugas.komponen', function($q) use ($id_mapel){
                    $q->where('id_mapel',$id_mapel);
                })
                ->get();

            return response()->json([
                'success'=>true,
                'data'=>$data
            ]);
        }

public function nilaiTugasKelas($id_kelas, $id_mapel)
{
    try {

        $data = \App\Models\NilaiTugas::with(['tugas','siswa'])
            ->whereHas('siswa', function($q) use ($id_kelas){
                $q->where('id_kelas', $id_kelas);
            })
            ->whereHas('tugas.komponen', function($q) use ($id_mapel){
                $q->where('id_mapel', $id_mapel);
            })
            ->get();

        return response()->json([
            'success'=>true,
            'data'=>$data
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success'=>false,
            'error'=>$e->getMessage()
        ], 500);
    }
}

        

        

    
}