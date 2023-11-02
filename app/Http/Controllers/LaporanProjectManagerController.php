<?php

namespace App\Http\Controllers;

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
                return '1';
            })
            ->addColumn('complete', function($data){
                return '2';
            })
            ->addColumn('action', function($data){
                return '<a href="'.route('laporan_project_manager.detail', $data->id).'" class="btn btn-warning btn-sm">
                    <span>
                        <i><img src="'.asset('assets/images/eye.svg').'" style="width: 15px;"></i>
                    </span>
                </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('laporan_project_manager.index');
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
        $data = OnRequest::select('pm_id', 'status')->with(['pm','pm.karyawan'])->get();

        $chartData = $data->groupBy('pm_id')->map(function (&$groupedData) {
            $onProgressCount = $groupedData->where('status', 1)->count();
            $completeCount = $groupedData->where('status', 2)->count();

            $employeeName = $groupedData->first()->pm->karyawan->name;

            return [
                'Employee' => $employeeName,
                'On Progress' => $onProgressCount,
                'Complete' => $completeCount,
            ];

        });

        return response()->json($chartData);
    }
}
