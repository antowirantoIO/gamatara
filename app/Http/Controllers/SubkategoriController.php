<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\ExportSubKategori;
use App\Models\SubKategori;
use App\Models\Kategori;

class SubkategoriController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SubKategori::with(['kategori'])->orderBy('name','asc')
                    ->filter($request);

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('kategori', function($data){
                return $data->kategori->name ?? '';
            })
            ->addColumn('action', function($data){
                return '<a href="'.route('sub_kategori.edit', $data->id).'" class="btn btn-success btn-sm">
                    <span>
                        <i><img src="'.asset('assets/images/edit.svg').'" style="width: 15px;"></i>
                    </span>
                </a>
                &nbsp;
                <a data-id="'.$data->id.'" data-name="sub_kategori '.$data->name.'" data-form="form-sub_kategori" class="btn btn-danger btn-sm deleteData">
                    <span>
                        <i><img src="'.asset('assets/images/trash.svg').'" style="width: 15px;"></i>
                    </span>
                </a>
                <form method="GET" id="form-sub_kategori'.$data->id.'" action="'.route('sub_kategori.delete', $data->id).'">
                    '.csrf_field().'
                    '.method_field('DELETE').'
                </form>';
            })
            ->rawColumns(['action'])
            ->make(true);                    
        }

        return view('sub_kategori.index');
    }

    public function create()
    {
        $kategori = Kategori::get();

        return view('sub_kategori.create',compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'kategori'  => 'required'
        ]);

        $data               = New SubKategori();
        $data->name         = $request->input('name');
        $data->id_kategori  = $request->input('kategori');
        $data->save();

        return redirect(route('sub_kategori'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function edit(Request $request)
    {
        $data = Subkategori::find($request->id);
        $kategori = Kategori::get();

        return view('sub_kategori.edit', Compact('data','kategori'));
    }

    public function updated(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'kategori'  => 'required'
        ]);

        $data               = SubKategori::find($request->id);
        $data->name         = $request->input('name');
        $data->id_kategori  = $request->input('kategori');
        $data->save();

        return redirect(route('sub_kategori'))
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
        $data = SubKategori::orderBy('name','desc')
                ->filter($request)
                ->get();

        return Excel::download(new ExportSubKategori($data), 'List Sub Kategori.xlsx');
    }
}
