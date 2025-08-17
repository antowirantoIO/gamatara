<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\ProjectAdmin;
use App\Models\OnRequest;
use Illuminate\Support\Facades\DB;
use App\Exports\ExportProjectAdmin;
use Maatwebsite\Excel\Facades\Excel;

class ProjectAdminController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Get all projects with their PA assignments
            $data = OnRequest::with(['pm', 'pm.karyawan', 'customer', 'pa', 'pa.karyawan'])
                ->select('project.*', 
                    DB::raw('(SELECT COUNT(*) FROM project_pekerjaan WHERE project_pekerjaan.id_project = project.id AND project_pekerjaan.status = 3) as completed_count'),
                    DB::raw('(SELECT COUNT(*) FROM project_pekerjaan WHERE project_pekerjaan.id_project = project.id) as total_count')
                )
                ->from('project')
                ->when($request->project_name, function($query, $projectName) {
                    return $query->where('nama_project', 'like', '%' . $projectName . '%');
                })
                ->when($request->status, function($query, $status) {
                    return $query->where('status', $status);
                })
                ->orderBy('created_at', 'desc');

            // Get all Project Admins for dynamic columns
            $pa = ProjectAdmin::with('karyawan')->get();

            $dataTable = Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('project_name', function($data){
                    return $data->nama_project;
                });

            // Add dynamic columns for each PA
            foreach ($pa as $admin) {
                $dataTable->addColumn('pa_' . $admin->id, function($data) use ($admin) {
                    // Check if this PA is assigned to this project
                    if ($data->pa_id == $admin->id) {
                        if ($data->status == 1) {
                            return '●'; // On Progress
                        } elseif ($data->status == 2) {
                            return '✓'; // Completed
                        } else {
                            return '○'; // Pending
                        }
                    }
                    return ''; // Empty if not assigned
                });
            }

            return $dataTable->rawColumns(['project_name'])->make(true);
        }

        // Get all Project Admins for the header
        $pa = ProjectAdmin::with('karyawan')->get();
        
        // Get summary data
        $totalProgress = OnRequest::where('status', 1)->count();
        $totalCompleted = OnRequest::where('status', 2)->count();

        $projectAdmins = $pa;

        return view('project_admin.index', compact('projectAdmins', 'totalProgress', 'totalCompleted'));
    }

    public function getProjectsByPA(Request $request, $paId)
    {
        // Get projects assigned to specific PA
        $data = OnRequest::with(['pm', 'pm.karyawan', 'customer'])
            ->whereHas('pa', function($query) use ($paId) {
                $query->where('id_karyawan', $paId);
            })
            ->select('project.*', 
                DB::raw('(SELECT COUNT(*) FROM project_pekerjaan WHERE project_pekerjaan.id_project = project.id AND project_pekerjaan.status = 3) as completed_count'),
                DB::raw('(SELECT COUNT(*) FROM project_pekerjaan WHERE project_pekerjaan.id_project = project.id) as total_count')
            )
            ->from('project')
            ->orderBy('created_at', 'desc');

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('project_name', function($data){
                return $data->nama_project;
            })
            ->addColumn('customer_name', function($data){
                return $data->customer ? $data->customer->name : '-';
            })
            ->addColumn('pm_name', function($data){
                return $data->pm && $data->pm->karyawan ? $data->pm->karyawan->name : '-';
            })
            ->addColumn('progress_status', function($data){
                if ($data->status == 1) {
                    return 'On Progress';
                } elseif ($data->status == 2) {
                    return 'Completed';
                } else {
                    return 'Pending';
                }
            })
            ->addColumn('progress_count', function($data){
                return $data->completed_count . ' / ' . $data->total_count;
            })
            ->rawColumns(['project_name', 'customer_name', 'pm_name', 'progress_status', 'progress_count'])
            ->make(true);
    }

    public function export(Request $request)
    {
        // Get filtered data for export
        $query = OnRequest::with(['pm', 'pm.karyawan', 'customer', 'pa', 'pa.karyawan'])
            ->select('project.*')
            ->from('project')
            ->when($request->project_name, function($query, $projectName) {
                return $query->where('nama_project', 'like', '%' . $projectName . '%');
            })
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->keyword, function($query, $keyword) {
                return $query->where('nama_project', 'like', '%' . $keyword . '%');
            })
            ->orderBy('created_at', 'desc');

        $data = $query->get();
        $projectAdmins = ProjectAdmin::with('karyawan')->get();

        $fileName = 'project_admin_report_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new ExportProjectAdmin($data, $projectAdmins), $fileName);
    }
}