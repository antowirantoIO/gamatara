<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\ExportLokasiProject;
use App\Models\LokasiProject;

class LokasiProjectController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = LokasiProject::orderBy('name','asc')
                    ->filter($request);

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function($data){
                $btnEdit = '';
                $btnDelete = '';
                if(Can('lokasi_project-edit')) {
                    $btnEdit = '<a href="'.route('lokasi_project.edit', $data->id).'" class="btn btn-success btn-sm">
                                    <span>
                                        <i><img src="'.asset('assets/images/edit.svg').'" style="width: 15px;"></i>
                                    </span>
                                </a>';
                }
                if(Can('lokasi_project-delete')){
                    $btnDelete = '<a data-id="'.$data->id.'" data-name="Lokasi Project '.$data->name.'" data-form="form-lokasi_project" class="btn btn-danger btn-sm deleteData">
                                    <span>
                                        <i><img src="'.asset('assets/images/trash.svg').'" style="width: 15px;"></i>
                                    </span>
                                </a>
                                <form method="GET" id="form-lokasi_project'.$data->id.'" action="'.route('lokasi_project.delete', $data->id).'">
                                    '.csrf_field().'
                                    '.method_field('DELETE').'
                                </form>';
                }
                return $btnEdit.'&nbsp;'.$btnDelete;
            })
            ->rawColumns(['action'])
            ->make(true);                    
        }

        return view('lokasi_project.index');
    }

    public function create()
    {
        return view('lokasi_project.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required'
        ]);

        $data       = New LokasiProject();
        $data->name = $request->input('name');
        $data->save();

        return redirect(route('lokasi_project'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function edit(Request $request)
    {
        $data = LokasiProject::find($request->id);

        return view('lokasi_project.edit', Compact('data'));
    }

    public function updated(Request $request)
    {
        $request->validate([
            'name'                  => 'required'
        ]);

        $data       = LokasiProject::find($request->id);
        $data->name = $request->input('name');
        $data->save();

        return redirect(route('lokasi_project'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function delete($id)
    {
        $data           = LokasiProject::findOrFail($id);
        $data->delete();

        return redirect(route('lokasi_project'))
                    ->with('success', 'Data berhasil dihapus');
    }
    
    public function export(Request $request)
    {
        $data = LokasiProject::orderBy('name','desc')
                ->filter($request)
                ->get();

        return Excel::download(new ExportLokasiProject($data), 'List Lokasi Project.xlsx');
    }
}
