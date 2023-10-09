<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\ExportProjectManager;
use App\Models\ProjectManager;
use App\Models\ProjectEngineer;
use App\Models\ProjectAdmin;
use App\Models\Karyawan;

class ProjectManagerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProjectManager::filter($request);

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('pm', function($data){
                return $data->karyawan->name;
            })
            ->addColumn('action', function($data){
                return '<a href="'.route('project_manager.edit', $data->id).'" class="btn btn-success btn-sm">
                    <span>
                        <i><img src="'.asset('assets/images/edit.svg').'" style="width: 15px;"></i>
                    </span>
                </a>
                &nbsp;
                <a data-id="'.$data->id.'" data-name="project_manager '.$data->name.'" data-form="form-project_manager" class="btn btn-danger btn-sm deleteData">
                    <span>
                        <i><img src="'.asset('assets/images/trash.svg').'" style="width: 15px;"></i>
                    </span>
                </a>
                <form method="GET" id="form-project_manager'.$data->id.'" action="'.route('project_manager.delete', $data->id).'">
                    '.csrf_field().'
                    '.method_field('DELETE').'
                </form>';
            })
            ->rawColumns(['action','pm'])
            ->make(true);                    
        }

        $karyawan = Karyawan::get();

        return view('project_manager.index',compact('karyawan'));
    }

    public function create()
    {
        $karyawan = Karyawan::get();

        return view('project_manager.create',compact('karyawan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pm'  => 'required',
            'pe'  => 'required',
            'pa'  => 'required'
        ]);

        $data               = New ProjectManager();
        $data->id_karyawan  = $request->input('pm');
        $data->save();

        $datas               = New ProjectEngineer();
        $datas->id_pm        = $data->id;
        $datas->id_karyawan  = $request->input('pe');
        $datas->save();

        $dataa               = New ProjectAdmin();
        $dataa->id_pm        = $data->id;
        $data->id_karyawan  = $request->input('pa');
        $data->save();

        return redirect(route('project_manager'))
                    ->with('success', 'Data berhasil disimpan');
    }
    
    public function delete($id)
    {
        $data           = ProjectManager::findOrFail($id);
        $data->delete();

        return redirect(route('project_manager'))
                    ->with('success', 'Data berhasil dihapus');
    }

    public function export(Request $request)
    {
        $data = ProjectManager::filter($request)
                ->get();

        return Excel::download(new ExportProjectManager($data), 'List Project Manager.xlsx');
    }
}
