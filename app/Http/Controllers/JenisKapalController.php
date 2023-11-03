<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\ExportJenisKapal;
use App\Models\JenisKapal;

class JenisKapalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = JenisKapal::orderBy('name','asc')
                    ->filter($request);

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function($data){
                $btnEdit = '';
                $btnDelete = '';
                if($this->authorize('jenis_kapal-edit')) {
                    $btnEdit = '<a href="'.route('jenis_kapal.edit', $data->id).'" class="btn btn-success btn-sm">
                                <span>
                                    <i><img src="'.asset('assets/images/edit.svg').'" style="width: 15px;"></i>
                                </span>
                            </a>';
                }
                if($this->authorize('jenis_kapal-delete')) {
                    $btnDelete = '<a data-id="'.$data->id.'" data-name="Jenis Kapal '.$data->name.'" data-form="form-jenis_kapal" class="btn btn-danger btn-sm deleteData">
                                    <span>
                                        <i><img src="'.asset('assets/images/trash.svg').'" style="width: 15px;"></i>
                                    </span>
                                </a>
                                <form method="GET" id="form-jenis_kapal'.$data->id.'" action="'.route('jenis_kapal.delete', $data->id).'">
                                    '.csrf_field().'
                                    '.method_field('DELETE').'
                                </form>';
                }
                return $btnEdit.'&nbsp;'.$btnDelete;
            })
            ->rawColumns(['action'])
            ->make(true);                    
        }

        return view('jenis_kapal.index');
    }

    public function create()
    {
        return view('jenis_kapal.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required'
        ]);

        $data       = New JenisKapal();
        $data->name = $request->input('name');
        $data->save();

        return redirect(route('jenis_kapal'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function edit(Request $request)
    {
        $data = JenisKapal::find($request->id);

        return view('jenis_kapal.edit', Compact('data'));
    }

    public function updated(Request $request)
    {
        $request->validate([
            'name'                  => 'required'
        ]);

        $data       = JenisKapal::find($request->id);
        $data->name = $request->input('name');
        $data->save();

        return redirect(route('jenis_kapal'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function delete($id)
    {
        $data           = JenisKapal::findOrFail($id);
        $data->delete();

        return redirect(route('jenis_kapal'))
                    ->with('success', 'Data berhasil dihapus');
    }
    
    public function export(Request $request)
    {
        $data = JenisKapal::orderBy('name','desc')
                ->filter($request)
                ->get();

        return Excel::download(new ExportJenisKapal($data), 'List Jenis Kapal.xlsx');
    }

}
