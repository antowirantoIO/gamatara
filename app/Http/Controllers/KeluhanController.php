<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keluhan;

class KeluhanController extends Controller
{
    public function store(Request $request)
    {        
        $keluhan                = new Keluhan();
        $keluhan->on_request_id = $request->id;
        $keluhan->keluhan       = $request->input('keluhan');
        $keluhan->save();

        return response()->json(['message' => 'Keluhan berhasil ditambahkan','status' => 200,'id' => $keluhan->id]);
    }

    public function delete(Request $request)
    {
        try {
            $keluhan = Keluhan::findOrFail($request->id);
            $keluhan->delete();
     
            return response()->json(['message' => 'Keluhan berhasil dihapus','status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus keluhan'], 500);
        }
    }
}
