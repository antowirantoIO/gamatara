<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\ProjectEngineer;
use App\Models\OnRequest;
use Illuminate\Support\Facades\DB;
use App\Exports\ExportProjectEngineerReport;
use Maatwebsite\Excel\Facades\Excel;

class ProjectEngineerReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Get all projects with their PE assignments
            $query = OnRequest::with(['pe', 'pe.karyawan', 'customer'])
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
            $projectEngineers = ProjectEngineer::with('karyawan')->get();

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

                // Add PE columns dynamically
                foreach ($projectEngineers as $pe) {
                    $columnKey = 'pe_' . $pe->id;
                    if ($project->pe_id_1 == $pe->id || $project->pe_id_2 == $pe->id) {
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

        $projectEngineers = ProjectEngineer::with('karyawan')->get();
        return view('project_engineer_report.index', compact('projectEngineers'));
    }

    public function export(Request $request)
    {
        // Get all projects with their PE assignments
        $query = OnRequest::with(['pe', 'pe.karyawan', 'customer'])
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
        $projectEngineers = ProjectEngineer::with('karyawan')->get();

        return Excel::download(new ExportProjectEngineerReport($projects, $projectEngineers), 'project_engineer_report.xlsx');
    }
}