<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\ExportLaporanCustomer;
use App\Models\Customer;
use App\Models\LokasiProject;
use App\Models\OnRequest;
use App\Models\ProjectPekerjaan;
use App\Exports\ExportReportProjectLocation;
use App\Exports\ExportReportProjectLocationDetail;
use DB;
use Carbon\Carbon;

class LaporanLokasiProjectController extends Controller
{
    public function index(Request $request)
    {
        $datas = LokasiProject::has('projects')
                ->when($request->filled('lokasi_id'), function ($query) use ($request) {
                    $query->whereHas('projects', function ($innerQuery) use ($request) {
                        $innerQuery->where('id_lokasi_project', $request->lokasi_id);
                    });
                })
                ->when($request->filled('daterange'), function ($query) use ($request) {
                    list($start_date, $end_date) = explode(' - ', $request->input('daterange'));
                    $query->whereHas('projects.progress', function ($query) use ($request, $start_date, $end_date) {
                        $query->whereBetween('created_at', [$start_date, $end_date]);
                    });
                })
                ->get();

                foreach ($datas as $value) {
                    if ($value->projects) {
                        $id = '';
                        foreach ($value->projects as $project) {
                            $id = $project->id_lokasi_project;
                        }
                        $value['total_project'] = $value->projects->count();
                        $value['detail_url'] = route('laporan_lokasi_project.detail', $id);
                    } else {
                        $value['total_project'] = 0;
                    }
                    $value['eye_image_url'] = "/assets/images/eye.svg";

                    $total = 0;

                    if ($value->projects) {
                        foreach ($value->projects as $values) { 
                            foreach ($values->progress as $project) {
                                $progress = $project ?? null;

                                if ($progress) {
                                    $total += $progress->harga_customer * $progress->qty;
                                }
                            }
                        }
                    }

                    $value['total'] = 'Rp ' . number_format($total, 0, ',', '.');
                }

                $datas = $datas->sortByDesc('total')->values();

                if($request->report_by){
                    return response()->json([
                        'datas' => $datas
                    ]);
                }

            $lokasi = LokasiProject::has('projects')->get();

        return view('laporan_lokasi_project.index', compact('lokasi','datas'));
    }

    public function dataChart(Request $request)
    {
        if($request->report_by != null)
        {
            $report_by = $request->report_by;
        }else{
            $report_by = 'tahun';
        }

        $data = ProjectPekerjaan::with(['projects'])
        ->when($request->filled('lokasi_id'), function ($query) use ($request) {
            $query->whereHas('projects', function ($innerQuery) use ($request) {
                $innerQuery->where('id_lokasi_project', $request->lokasi_id);
            });
        })
        ->when($request->filled('daterange'), function ($query) use ($request) {
            list($start_date, $end_date) = explode(' - ', $request->input('daterange'));
            return $query->whereBetween('created_at', [$start_date, $end_date]);
        })  
        ->get()
        ->groupBy('projects.id_lokasi_project');

        $data_customer = [];
        $date = [];
        $result = $data->map(function ($groupedItems) use ($report_by) {
            return $groupedItems->groupBy(function ($item) use ($report_by) {
                switch ($report_by) {
                    case 'bulan':
                        return $item->created_at->format('Y-m');
                    case 'tahun':
                        return $item->created_at->format('Y');
                    case 'tanggal':
                    default:
                        return $item->created_at->toDateString();
                }
            });
        });
      
        foreach($result as $keyId => $value){
            $price_project = [];
            foreach($value as $keyDate => $item) {
                if(!in_array($keyDate, $date))
                    $date[] = $keyDate;

                $price_project[$keyId][] = $item->sum(function ($individualItem) {
                    return ($individualItem->harga_customer ?? 0) * ($individualItem->qty ?? 0);
                });
            }
            $data_customer[] = [
                'name' => $item->first()->projects->lokasi->name ?? '',
                'data' => $price_project[$keyId]
            ];
        }

        return response()->json([
            'date' => array_values($date),
            'data_customer' => $data_customer
        ]);
    }
    
    public function detail(Request $request)
    {
        if ($request->ajax()) {
            $data = OnRequest::has('progress')
                    ->where('id_lokasi_project', $request->id)
                    ->filter($request)
                    ->orderBy('created_at','desc')
                    ->get();
            // $cekIds = $cek->pluck('id')->toArray();
            // $data = ProjectPekerjaan::with('projects')->whereIn('id_project',$cekIds)
            //         ->addSelect(['total' => OnRequest::selectRaw('count(*)')
            //             ->whereColumn('project_pekerjaan.id_project', 'project.id')
            //             ->groupBy('id_lokasi_project')
            //         ])
            //         ->filter($request);

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('code', function($data){
                return $data->code ?? '';
            })
            ->addColumn('nama_project', function($data){
                return $data->nama_project ?? '';
            })
            ->addColumn('nilai_project', function($data){
                $totalHarga = 0;
        
                foreach ($data->progress as $progress) {
                    $totalHarga += $progress->harga_customer * $progress->qty;
                }

                if (is_numeric($totalHarga)) {
                    return 'Rp ' . number_format($totalHarga, 0, ',', '.');
                } else {
                    return 'Rp 0000';
                }
            })
            ->addColumn('tanggal_mulai', function($data){
                return $data && $data->created_at ? $data->created_at->format('d M Y') : '';
            })
            ->addColumn('tanggal_selesai', function($data){
                return $data && $data->actual_selesai ? $data->actual_selesai->format('d M Y') : '';
            })  
            ->addColumn('status_project', function($data){
               if($data->status == 1){
                    $status = '<span style="color: blue;">Progress</span>';
                }else if($data->status == 2){
                    $status = '<span style="color: green;">Complete</span>';
                }else{
                    $status = '-';
                }
                return $status;
            })
            ->rawColumns(['status_project','tanggal_mulai','nilai_project'])
            ->make(true);                    
        }

        $data = OnRequest::where('id_lokasi_project',$request->id)->first();

        return view('laporan_lokasi_project.detail', compact('data'));
    }

    public function export(Request $request)
    {
        $data = LokasiProject::has('projects')
                ->with('projects','projects.progress')
                ->get();
        
        foreach($data as $value){
            if($value->projects)
            {
                $value['total_project'] = $value->projects->count();
            }else{
                $value['total_project'] = 0;
            }

            $totalHargaCustomer = 0;
        
            if ($value->projects) {
                foreach($value->projects as $values){
                    foreach ($values->progress as $project) {
                        $progress = $project ?? null;
            
                        if ($progress) {
                            $totalHargaCustomer += $progress->harga_customer * $progress->qty;
                        }
                    }
                }
            }
        
            $value['totalHargaCustomer'] = 'Rp '. number_format($totalHargaCustomer, 0, ',', '.');
        }

        return Excel::download(new ExportReportProjectLocation($data), 'Report Project Location.xlsx');
    }

    public function exportDetail(Request $request)
    {
        $data = OnRequest::has('progress')
                ->where('id_lokasi_project', $request->id)
                ->filter($request)
                ->orderBy('created_at','desc')
                ->get();
        // $cekIds = $cek->pluck('id')->toArray();
        // $data = ProjectPekerjaan::with('projects')->whereIn('id_project',$cekIds)
        //         ->addSelect(['total' => OnRequest::selectRaw('count(*)')
        //             ->whereColumn('project_pekerjaan.id_project', 'project.id')
        //             ->groupBy('id_lokasi_project')
        //         ])
        //         ->filter($request)
        //         ->get();

        foreach($data as $value){
            $totalHarga = 0;
            
            foreach ($value->progress as $progress) {
                $totalHarga += $progress->harga_customer * $progress->qty;
            }

            if (is_numeric($totalHarga)) {
                $value['nilai_project'] = 'Rp ' . number_format($totalHarga, 0, ',', '.');
            } else {
                $value['nilai_project'] = 'Rp 0000';
            }

            if($value->status == 1){
                $value['stat'] = 'Progress';
            }else if($value->status == 2){
                $value['stat'] = 'Complete';
            }else{
                $value['stat'] = '-';
            }
        }

        return Excel::download(new ExportReportProjectLocationDetail($data), 'Report Project Location Detail.xlsx');
    }
}
