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
            ->addColumn('harga_customer', function($data){
                return number_format($data->harga_customer, 0, ',', '.');
            })
            ->addColumn('harga_vendor', function($data){
                return number_format($data->harga_vendor, 0, ',', '.');
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
            'name'              =>  'required',
            'unit'              =>  'required',
            'konversi'          =>  'required',
            'harga_vendor'      =>  'required',
            'harga_customer'    =>  'required'    
        ]);

        $data                   = New Pekerjaan();
        $data->name             = $request->input('name');
        $data->unit             = $request->input('unit');
        $data->konversi         = $request->input('konversi');
        $data->harga_customer   = str_replace(".", "", $request->harga_customer);
        $data->harga_vendor     = str_replace(".", "", $request->harga_vendor);
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
            'name'              =>  'required',
            'unit'              =>  'required',
            'konversi'          =>  'required',
            'harga_vendor'      =>  'required',
            'harga_customer'    =>  'required'    
        ]);

        $data                   = Pekerjaan::find($request->id);
        $data->name             = $request->input('name');
        $data->unit             = $request->input('unit');
        $data->konversi         = $request->input('konversi');
        $data->harga_customer   = str_replace(".", "", $request->harga_customer);
        $data->harga_vendor     = str_replace(".", "", $request->harga_vendor);
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
