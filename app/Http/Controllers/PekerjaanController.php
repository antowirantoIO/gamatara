<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\ExportPekerjaan;
use App\Models\Pekerjaan;

class PekerjaanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pekerjaan::orderBy('name','asc')
                    ->filter($request);

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('unit', function($data){
                return $data->unit ?? '-';
            })
            ->addColumn('action', function($data){
                return '<a href="'.route('pekerjaan.edit', $data->id).'" class="btn btn-success btn-sm">
                    <span>
                        <i><img src="'.asset('assets/images/edit.svg').'" style="width: 15px;"></i>
                    </span>
                </a>
                &nbsp;
                <a data-id="'.$data->id.'" data-name="pekerjaan '.$data->name.'" data-form="form-pekerjaan" class="btn btn-danger btn-sm deleteData">
                    <span>
                        <i><img src="'.asset('assets/images/trash.svg').'" style="width: 15px;"></i>
                    </span>
                </a>
                <form method="GET" id="form-pekerjaan'.$data->id.'" action="'.route('pekerjaan.delete', $data->id).'">
                    '.csrf_field().'
                    '.method_field('DELETE').'
                </form>';
            })
            ->rawColumns(['action'])
            ->make(true);                    
        }

        return view('pekerjaan.index');
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

    public function export(Request $request)
    {
        $data = Pekerjaan::orderBy('name','desc')
                ->filter($request)
                ->get();

        return Excel::download(new ExportPekerjaan($data), 'List Pekerjaan.xlsx');
    }
    
}
