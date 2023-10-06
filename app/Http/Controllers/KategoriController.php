<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\ExportKategori;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Kategori::orderBy('name','asc')
                    ->filter($request);

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function($data){
                return '<a href="'.route('kategori.edit', $data->id).'" class="btn btn-success btn-sm">
                    <span>
                        <i><img src="'.asset('assets/images/edit.svg').'" style="width: 15px;"></i>
                    </span>
                </a>
                &nbsp;
                <a data-id="'.$data->id.'" data-name="kategori '.$data->name.'" data-form="form-kategori" class="btn btn-danger btn-sm deleteData">
                    <span>
                        <i><img src="'.asset('assets/images/trash.svg').'" style="width: 15px;"></i>
                    </span>
                </a>
                <form method="GET" id="form-kategori'.$data->id.'" action="'.route('kategori.delete', $data->id).'">
                    '.csrf_field().'
                    '.method_field('DELETE').'
                </form>';
            })
            ->rawColumns(['action'])
            ->make(true);                    
        }

        return view('kategori.index');
    }

    public function create()
    {
        return view('sub_kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required'
        ]);

        $data       = New Kategori();
        $data->name = $request->input('name');
        $data->save();

        return redirect(route('kategori'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function edit(Request $request)
    {
        $data = kategori::find($request->id);

        return view('kategori.edit', Compact('data'));
    }

    public function updated(Request $request)
    {
        $request->validate([
            'name'                  => 'required'
        ]);

        $data       = Kategori::find($request->id);
        $data->name = $request->input('name');
        $data->save();

        return redirect(route('kategori'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function delete($id)
    {
        $data           = Kategori::findOrFail($id);
        $data->delete();

        return redirect(route('kategori'))
                    ->with('success', 'Data berhasil dihapus');
    }
    
    public function export(Request $request)
    {
        $data = Kategori::orderBy('name','desc')
                ->filter($request)
                ->get();

        return Excel::download(new ExportKategori($data), 'List Kategori.xlsx');
    }
}
