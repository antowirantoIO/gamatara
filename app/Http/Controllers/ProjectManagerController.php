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
        $selectedPM = ProjectManager::select('id_karyawan')->get()->pluck('id_karyawan')->toArray();
        $selectedPE = ProjectEngineer::select('id_karyawan')->get()->pluck('id_karyawan')->toArray();
        $selectedPA = ProjectAdmin::select('id_karyawan')->get()->pluck('id_karyawan')->toArray();
 
        $selected = array_merge($selectedPM, $selectedPE, $selectedPA);
        // sort($selected);
        // $selected = implode(', ', $selected);

        return view('project_manager.create',compact('karyawan','selected'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pm'            => 'required',
            'pe'    => 'required',
            'pa'    => 'required'
        ]);

        $data               = New ProjectManager();
        $data->id_karyawan  = $request->input('pm');
        $data->save();

        foreach($request->pe as $selectedPEId) {
            $dataPE = new ProjectEngineer();
            $dataPE->id_pm = $data->id;
            $dataPE->id_karyawan = $selectedPEId;
            $dataPE->save();
        }

        foreach($request->pa as $selectedPAId) {
            $dataPA = new ProjectAdmin();
            $dataPA->id_pm = $data->id;
            $dataPA->id_karyawan = $selectedPAId;
            $dataPA->save();
        }

        return redirect(route('project_manager'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function edit(Request $request)
    {
        $data = ProjectManager::find($request->id);
        $datas = ProjectEngineer::where('id_pm',$request->id)->get();
        $datass = ProjectAdmin::where('id_pm',$request->id)->get();
 
        $karyawan = Karyawan::get();
        $selectedPE = ProjectEngineer::where('id_pm', $request->id)->get()->pluck('id_karyawan')->toArray();
        $selectedPA = ProjectAdmin::where('id_pm', $request->id)->get()->pluck('id_karyawan')->toArray();
 
        $notSelectedPM = ProjectManager::whereNotIn('id_karyawan', [$data->id_karyawan])
            ->select('id_karyawan')
            ->get()
            ->pluck('id_karyawan')
            ->toArray();
    
        $notSelectedPE = ProjectEngineer::whereNotIn('id_pm', [$request->id])
            ->select('id_karyawan')
            ->get()
            ->pluck('id_karyawan')
            ->toArray();

        $notSelectedPA = ProjectAdmin::whereNotIn('id_pm', [$request->id])
            ->select('id_karyawan')
            ->get()
            ->pluck('id_karyawan')
            ->toArray();
        
        $notSelected = array_merge($notSelectedPM, $notSelectedPE, $notSelectedPA);

        return view('project_manager.edit',compact('data','notSelected','karyawan','selectedPE','selectedPA'));
    }

    public function updated(Request $request)
    {
        $request->validate([
            'pm'    => 'required',
            'pe'    => 'required|array',
            'pa'    => 'required|array'
        ]);

        $data               = ProjectManager::find($request->id);
        $data->id_karyawan  = $request->input('pm');
        $data->save();

        $data->pe()->delete();
        $data->pa()->delete();

        foreach($request->pe as $selectedPEId) {
            $dataPE                 = New ProjectEngineer();
            $dataPE->id_pm          = $data->id;
            $dataPE->id_karyawan    = $selectedPEId;
            $dataPE->save();
        }

        foreach($request->pa as $selectedPAId) {
            $dataPA                 = New ProjectAdmin();
            $dataPA->id_pm          = $data->id;
            $dataPA->id_karyawan    = $selectedPAId;
            $dataPA->save();
        }

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
