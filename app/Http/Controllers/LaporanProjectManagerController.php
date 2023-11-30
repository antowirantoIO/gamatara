<?php

namespace App\Http\Controllers;

use App\Exports\ExportLaporanProjectManager;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\ExportLaporanVendor;
use App\Models\OnRequest;
use App\Models\ProjectManager;

class LaporanProjectManagerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProjectManager::filter($request);

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('name', function($data){
                return $data->karyawan->name ?? '';
            })
            ->addColumn('on_progress', function($data){
                return $data->projects->where('status', 2)->count();
            })
            ->addColumn('complete', function($data){
                return $data->projects->where('status', 3)->count();
            })
            ->addColumn('action', function($data){
                return '<a href="'.route('laporan_project_manager.detail', $data->id).'" class="btn btn-warning btn-sm">
                    <span>
                        <i><img src="'.asset('assets/images/eye.svg').'" style="width: 15px;"></i>
                    </span>
                </a>';
            })
            ->filter(function ($query) use ($request) {

                if (!empty($request->input('name'))) {
                   $query->where('id', $request->name);
                }

                if (!empty($request->input('on_progress'))) {
                    $query->whereHas('projects', function ($subQuery) use ($request) {
                        $subQuery->where('status', 2)
                            ->havingRaw('COUNT(*) = ?', [$request->input('on_progress')]);
                    });
                }

                if (!empty($request->input('complete'))) {
                    $query->whereHas('projects', function ($subQuery) use ($request) {
                        $subQuery->where('status', 3)
                            ->havingRaw('COUNT(*) = ?', [$request->input('complete')]);
                    });
                }

            })
            ->rawColumns(['action'])
            ->make(true);
        }
        $project_manager = ProjectManager::all();
        return view('laporan_project_manager.index',compact('project_manager'));
    }

    public function detail(Request $request, $id)
    {
        $pm = ProjectManager::where('id',$id)->first();
        if($request->ajax()){
            $data = OnRequest::where('pm_id',$id)
                            ->with(['pm','pm.karyawan','customer','progress']);

            return Datatables::of($data)->addIndexColumn()
            ->make(true);
        }

        return view('laporan_project_manager.detail',compact('id','pm'));
    }

    public function chart(Request $request)
    {
        if($request->year)
        {
            $tahun = $request->year;
        }else{
            $tahun = now()->format('Y');
        }

        $data = OnRequest::select('pm_id', 'status')
                        ->with(['pm','pm.karyawan'])
                        ->whereYear('created_at',$tahun)
                        ->get();

        $chartData = $data->groupBy('pm_id')->map(function (&$groupedData) {
            $onProgressCount = $groupedData->where('status', 2)->count();
            $completeCount = $groupedData->where('status', 3)->count();

            $employeeName = $groupedData->first()->pm->karyawan->name;

            return [
                'Employee' => $employeeName,
                'On Progress' => $onProgressCount,
                'Complete' => $completeCount,
            ];

        });

        return response()->json($chartData);
    }

    public function export(Request $request)
    {
        $data = ProjectManager::all();
        return Excel::download(new ExportLaporanProjectManager($data),'Laporan Pekerjaan PM.xlsx');
        return view('export.ExportLaporanManager',compact('data'));
    }
}
