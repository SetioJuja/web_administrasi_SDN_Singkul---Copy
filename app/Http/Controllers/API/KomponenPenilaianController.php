<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KomponenPenilaian;

class KomponenPenilaianController extends Controller
{
    public function index()
    {
        return response()->json([
            'success'=>true,
            'data'=>KomponenPenilaian::with(['mapel','guru'])->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_mapel'=>'required',
            'id_guru'=>'required',
            'nama_komponen'=>'required',
            'bobot'=>'required|numeric'
        ]);

        $data = KomponenPenilaian::create($validated);

        return response()->json([
            'success'=>true,
            'data'=>$data
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'success'=>true,
            'data'=>KomponenPenilaian::find($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = KomponenPenilaian::find($id);

        if(!$data){
            return response()->json(['success'=>false],404);
        }

        $data->update($request->all());

        return response()->json(['success'=>true]);
    }

    public function destroy($id)
    {
        KomponenPenilaian::destroy($id);

        return response()->json(['success'=>true]);
    }

//     public function byGuru($id_guru)
// {
//     $data = \App\Models\KomponenPenilaian::with(['mapel','guru'])
//         ->where('id_guru', $id_guru)
//         ->get();

//     return response()->json([
//         'success'=>true,
//         'data'=>$data
//     ]);
// }

public function byGuru($id_guru)
{
    $data = KomponenPenilaian::with([
            'mapel',
            'guru'
        ])
        ->where('id_guru', $id_guru)
        ->get();

    return response()->json([
        'success'=>true,
        'data'=>$data
    ]);
}


}