<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\ProjectManager;
use App\Models\OnRequest;
use Illuminate\Support\Facades\DB;
use App\Exports\ExportProjectManagerReport;
use Maatwebsite\Excel\Facades\Excel;

class ProjectManagerReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Get all projects with their PM assignments
            $query = OnRequest::with(['pm', 'pm.karyawan', 'customer'])
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

            $projects = $query->get();
            $projectManagers = ProjectManager::with('karyawan')->get();

            // Transform data for DataTables
            $transformedData = [];
            foreach ($projects as $project) {
                $row = [
                    'project_name' => $project->nama_project,
                    'customer_name' => $project->customer->nama ?? '',
                    'request_date' => $project->created_at ? $project->created_at->format('d/m/Y') : '',
                    'displacement_ship' => $project->displacement_ship ?? '',
                    'ship_type' => $project->jenis_kapal ?? '',
                ];

                // Add PM columns dynamically
                foreach ($projectManagers as $pm) {
                    $columnKey = 'pm_' . $pm->id;
                    if ($project->pm_id == $pm->id) {
                        if ($project->status == 1) {
                            $row[$columnKey] = '●'; // On Progress
                        } elseif ($project->status == 2) {
                            $row[$columnKey] = '✓'; // Completed
                        } else {
                            $row[$columnKey] = '○'; // Pending
                        }
                    } else {
                        $row[$columnKey] = '';
                    }
                }

                $transformedData[] = $row;
            }

            return DataTables::of(collect($transformedData))
                ->addIndexColumn()
                ->rawColumns(['project_name', 'customer_name'])
                ->make(true);
        }

        $projectManagers = ProjectManager::with('karyawan')->get();
        return view('project_manager_report.index', compact('projectManagers'));
    }

    public function export(Request $request)
    {
        // Get filtered data for export
        $query = OnRequest::with(['pm', 'pm.karyawan', 'customer'])
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
        $projectManagers = ProjectManager::with('karyawan')->get();

        $fileName = 'project_manager_report_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new ExportProjectManagerReport($data, $projectManagers), $fileName);
    }
}