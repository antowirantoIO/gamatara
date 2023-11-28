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
        $datas = ProjectManager::with(['projects'])
        ->when($request->filled('project_manager_id'), function ($query) use ($request) {
            $query->whereHas('projects', function ($innerQuery) use ($request) {
                $innerQuery->where('pm_id', $request->project_manager_id);
            });
        })
        ->when($request->filled('daterange'), function ($query) use ($request) {
            list($start_date, $end_date) = explode(' - ', $request->input('daterange'));
            $query->whereHas('projects', function ($query) use ($request, $start_date, $end_date) {
                $query->whereBetween('created_at', [$start_date, $end_date]);
            });
        })
        ->get();

        foreach ($datas as $value) {
            if ($value->first()->projects) {
                $value['detail_url'] = route('laporan_project_manager.detail', $value->first()->id);
                $value['name'] = $value->first()->karyawan->name ?? '';
                $value['onprogress'] = $value->first()->projects->where('status', 1)->count();
                $value['complete'] = $value->first()->projects->where('status', 2)->count();
                $value['eye_image_url'] = "/assets/images/eye.svg";
            }
        }
        $datas = $datas->values();

        if($request->report_by){
            return response()->json([
                'datas' => $datas
            ]);
        }

        $pm = ProjectManager::has('projects')->get();
    
        return view('laporan_project_manager.index',compact('pm','datas'));
    }

    public function dataChartt(Request $request)
    {
        if($request->report_by != null)
        {
            $report_by = $request->report_by;
        }else{
            $report_by = 'tahun';
        }

        $datas = ProjectManager::with(['projects' => function ($query) use ($request) {
                $query->select('pm_id', 'status', 'created_at')
                    ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as onprogress')
                    ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as complete')
                    ->groupBy('pm_id', 'status', 'created_at');
            }])
            ->when($request->filled('project_manager_id'), function ($query) use ($request) {
                $query->where('id', $request->project_manager_id);
            })
            ->when($request->filled('daterange'), function ($query) use ($request) {
                list($start_date, $end_date) = explode(' - ', $request->input('daterange'));
                $query->whereHas('projects', function ($query) use ($request, $start_date, $end_date) {
                    $query->whereBetween('created_at', [$start_date, $end_date]);
                });
            })
            ->get();
    
            $data_pm = [];
            $date = [];
            
            foreach ($datas as $keyId => $value) {
                $projects = $value->projects;
            
                foreach ($projects as $project) {
                    $name = $project->pm->karyawan->name;
                    $dateKey = $project->created_at->format('Y-m-d');
            
                    $data_pm[$name][$dateKey] = [
                        'onprogress' => $project->onprogress,
                        'complete' => $project->complete,
                    ];
            
                    if (!in_array($dateKey, $date)) {
                        $date[] = $dateKey;
                    }
                }
            }
            
            $result = [];
            foreach ($data_pm as $name => $data) {
                $rowData = [
                    'name' => $name,
                    'data' => [],
                ];
            
                foreach ($date as $dateKey) {
                    $rowData['data'][] = [
                        'onprogress' => $data[$dateKey]['onprogress'] ?? 0,
                        'complete' => $data[$dateKey]['complete'] ?? 0,
                    ];
                }
            
                $result[] = $rowData;
            }
            
            return response()->json([
                'date' => array_values($date),
                'data_pm' => $result,
            ]);
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
