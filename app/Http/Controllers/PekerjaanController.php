<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pekerjaan;

class PekerjaanController extends Controller
{
    public function index()
    {
        $data = pekerjaan::get();

        return view('pekerjaan.index', Compact('data'));
    }
    
    public function create()
    {
        return view('pekerjaan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required',
        ]);

        $data                           = New Pekerjaan();
        $data->name                     = $request->input('name');
        $data->length                   = $request->input('length');
        $data->width                    = $request->input('width');
        $data->thick                    = $request->input('thick');
        $data->unit                     = $request->input('unit');
        $data->conversion               = $request->input('conversion');
        $data->save();

        return redirect(route('pekerjaan'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function edit(Request $request)
    {
        $data = Pekerjaan::find($request->id);

        return view('pekerjaan.edit', Compact('data'));
    }

    public function updated(Request $request)
    {
        $request->validate([
            'name'   => 'required',
        ]);

        $data                           = Pekerjaan::find($request->id);
        $data->name                     = $request->input('name');
        $data->length                   = $request->input('length');
        $data->width                    = $request->input('width');
        $data->thick                    = $request->input('thick');
        $data->unit                     = $request->input('unit');
        $data->conversion               = $request->input('conversion');
        $data->save();

        return redirect(route('pekerjaan'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function delete($id)
    {
        $data           = Pekerjaan::findOrFail($id);
        $data->delete();

        return redirect(route('pekerjaan'))
                    ->with('success', 'Data berhasil dihapus');
    }
    
}
