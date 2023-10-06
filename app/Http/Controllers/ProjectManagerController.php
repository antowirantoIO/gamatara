<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\ExportProjectManager;
use App\Models\ProjectManager;
use App\Models\ProjectEngineer;
use App\Models\ProjectAdmin;

class ProjectManagerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProjectManager::filter($request);

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('pm', function($data){
                return $data->karyawan->name ?? '';
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
            ->rawColumns(['action'])
            ->make(true);                    
        }

        return view('project_manager.index');
    }

    public function create()
    {
        return view('project_manager.create');
    }
}
