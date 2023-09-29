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
            'kategori_pekerjaan'   => 'required',
        ]);

        $data                           = New Pekerjaan();
        $data->kategori_pekerjaan       = $request->input('kategori_pekerjaan');
        $data->sub_kategori_pekerjaan   = $request->input('sub_kategori_pekerjaan');
        $data->jenis_pekerjaan          = $request->input('jenis_pekerjaan');
        $data->detailother              = $request->input('detailother');
        $data->length                   = $request->input('length');
        $data->width                    = $request->input('width');
        $data->thick                    = $request->input('thick');
        $data->unit                     = $request->input('unit');
        $data->harga_vendor             = $request->input('harga_vendor');
        $data->harga_customer           = $request->input('harga_customer');
        $data->convert                  = $request->input('convert');
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
            'kategori_pekerjaan'   => 'required',
        ]);

        $data                           = Pekerjaan::find($request->id);
        $data->kategori_pekerjaan       = $request->input('kategori_pekerjaan');
        $data->sub_kategori_pekerjaan   = $request->input('sub_kategori_pekerjaan');
        $data->jenis_pekerjaan          = $request->input('jenis_pekerjaan');
        $data->detailother              = $request->input('detailother');
        $data->length                   = $request->input('length');
        $data->width                    = $request->input('width');
        $data->thick                    = $request->input('thick');
        $data->unit                     = $request->input('unit');
        $data->harga_vendor             = $request->input('harga_vendor');
        $data->harga_customer           = $request->input('harga_customer');
        $data->convert                  = $request->input('convert');
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
