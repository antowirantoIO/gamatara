<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Customer;
use App\Models\OnRequest;
use App\Models\ProjectPekerjaan;
use App\Models\Vendor;
use App\Models\Kategori;
use App\Models\SubKategori;
use App\Models\Pekerjaan;
use App\Models\SettingPekerjaan;
use App\Models\BeforePhoto;
use App\Models\AfterPhoto;
use App\Models\ProjectManager;
use App\Models\Keluhan;
use DB;

class BodController extends Controller
{
    public function laporanCustomer(Request $request)
    {
        try{
            $perPage = 5;
            $page = request()->get('page', $request->page);
            
            $data = Customer::select('id', 'name')->has('projects')
                ->with('projects', 'projects.progress:harga_customer,qty,id_project')
                ->get();
            
            $dataArray = $data->map(function ($value) {
                $result = [
                    'id' => $value->id,
                    'name' => $value->name,
                ];
            
                if ($value->projects) {
                    $result['jumlah_project'] = $value->projects->count();
                } else {
                    $result['jumlah_project'] = 0;
                }
            
                $jumlah_tagihan = 0;
            
                if ($value->projects) {
                    foreach ($value->projects as $values) {
                        foreach ($values->progress as $project) {
                            $progress = $project ?? null;
            
                            if ($progress) {
                                $jumlah_tagihan += $progress->harga_customer * $progress->qty;
                            }
                        }
                    }
                }
            
                $result['jumlah_tagihan'] = 'Rp ' . number_format($jumlah_tagihan, 0, ',', '.');
            
                return $result;
            });
            
            $dataArray = collect($dataArray)->sortByDesc('jumlah_tagihan');
            
            // Manually create paginated data
            $currentPageItems = $dataArray->splice(($page - 1) * $perPage, $perPage)->values();
            
            $paginator = new LengthAwarePaginator(
                $currentPageItems,
                $dataArray->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => 'page',
                ]
            );

            if($request->tahun != null)
            {
                $tahun = $request->tahun;
            }else{
                $tahun = now()->format('Y');
            }
            
            $totalHargaPerBulan = array_fill(0, 12, 0);

            $datas = ProjectPekerjaan::selectRaw('MONTH(created_at) as month, SUM(harga_customer * qty) as total_harga')
                ->whereYear('created_at', $tahun)
                ->groupBy('month')
                ->orderBy('month')
                ->get();

                $totalHarga = [];

            
            foreach ($datas as $item) {
                $totalHargaPerBulan[$item->month - 1] = $item->total_harga;
            }
            
            for ($i = 0; $i < 12; $i++) {
                if (!isset($totalHargaPerBulan[$i])) {
                    $totalHargaPerBulan[$i] = 0;
                }
            }
            
            $arrayChart = json_encode($totalHargaPerBulan, JSON_NUMERIC_CHECK);

            return response()->json(['success' => true, 'message' => 'success', 'data' => $paginator,'chart'=> $arrayChart]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function laporanVendor(Request $request)
    {
        try{
            $perPage = 5;
            $page = request()->get('page', $request->page);

            $data = Vendor::select('id','name')
                    ->with('projectPekerjaan:id,harga_vendor,qty,id_project')
                    ->get();
        
            foreach($data as $value){
                if($value->projects)
                {
                    $value['jumlah_project'] = $value->projects->count();
                }else{
                    $value['jumlah_project'] = 0;
                }

                $jumlah_tagihan = 0;
            
                if ($value->projects) {
                    foreach($value->projects as $values){
                        foreach ($values->progress as $project) {
                            $progress = $project ?? null;
                
                            if ($progress) {
                                $jumlah_tagihan += $progress->harga_vendor * $progress->qty;
                            }
                        }
                    }
                }
            
                $value['jumlah_tagihan'] = 'Rp '. number_format($jumlah_tagihan, 0, ',', '.');
            }

            $data = $data->values();

            $paginator = new LengthAwarePaginator(
                $data->forPage($page, $perPage),
                $data->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => 'page',
                ]
            );

            if($request->tahun != null)
            {
                $tahun = $request->tahun;
            }else{
                $tahun = now()->format('Y');
            }

            $byTonase = Vendor::join('project_pekerjaan as B', 'vendor.id', '=', 'B.id_vendor')
                        ->join('project as C', function ($join) use ($tahun) {
                            $join->on('B.id_project', '=', 'C.id')
                                ->where('B.id_kategori', '=', 3)
                                ->whereYear('C.created_at', '=', $tahun);
                        })
                        ->select('vendor.id', 'vendor.name', DB::raw('SUM(B.amount) as tonase'))
                        ->groupBy('vendor.id', 'vendor.name')
                        ->orderByDesc(DB::raw('SUM(B.amount)'))
                        ->get();

            $byVolume = Vendor::join('project_pekerjaan as B', 'vendor.id', '=', 'B.id_vendor')
                        ->join('project as C', function ($join) use ($tahun) {
                            $join->on('B.id_project', '=', 'C.id')
                                ->where('B.id_kategori', '=', 2)
                                ->whereRaw('YEAR(C.created_at) = ?', [$tahun]);
                        })
                        ->select('vendor.id', 'vendor.name', \DB::raw('SUM(B.amount) as volume'))
                        ->groupBy('vendor.id', 'vendor.name')
                        ->orderByDesc(\DB::raw('SUM(B.amount)'))
                        ->get();

            return response()->json(['success' => true, 'message' => 'success', 'data' => $paginator, 'byTonase'=> $byTonase , 'byVolume' => $byVolume]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function laporanPM(Request $request)
    {
        try{
            $perPage = 5;
            $page = request()->get('page', $request->page);

            if($request->tahun != null)
            {
                $tahun = $request->tahun;
            }else{
                $tahun = now()->format('Y');
            }

            $data = ProjectManager::get();
            foreach($data as $item)
            {
                $item['name'] = $item->karyawan->name ?? '';
                $item['onprogress'] = $item->projects->where('status', 1)->count();
                $item['complete'] = $item->projects->where('status', 2)->count();
            }

            $data = $data->values();

            $paginator = new LengthAwarePaginator(
                $data->forPage($page, $perPage),
                $data->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => 'page',
                ]
            );
      
            $chart = OnRequest::select('pm_id', 'status','created_at')
                    ->with(['pm','pm.karyawan'])
                    ->whereYear('created_at',$tahun)
                    ->get();

            $chartData = $chart->groupBy('pm_id')->map(function ($groupedData) {
                $onProgressCount = $groupedData->where('status', 1)->count();
                $completeCount = $groupedData->where('status', 2)->count();
            
                $employeeName = $groupedData->first()->pm->karyawan->name;
            
                return [
                    'name' => $employeeName,
                    'on_progress' => $onProgressCount,
                    'complete' => $completeCount,
                ];
            })->values();

            return response()->json(['success' => true, 'message' => 'success', 'data' => $paginator ,'chart_progress' => $chartData]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function index(Request $request)
    {
        try{
            $data = OnRequest::with(['complaint','customer:id,name'])
                ->select('A.nama_project', 'A.created_at', 'A.id','A.id_customer',
                    DB::raw('(SELECT COUNT(id_Pekerjaan) FROM project_pekerjaan WHERE status = 3 AND id_project = A.id) AS done'), 
                    DB::raw('(SELECT COUNT(id_Pekerjaan) FROM project_pekerjaan WHERE id_project = A.id) AS total'), 'A.status')
                ->from('Project as A')
                ->leftJoin('project_pekerjaan as b', 'A.id', '=', 'b.id_project')
                ->where('A.status', 1)
                ->groupBy('A.id', 'A.nama_project', 'A.created_at', 'A.id_customer', 'A.status')
                ->orderByDesc('A.created_at')
                ->get();

            foreach ($data as $item) {
                $item['nama_customer'] = $item->customer->name ?? '-';
                $item['tanggal'] = $item->created_at ? date('d M Y', strtotime($item->created_at)) : '-';
                $item['progress_pekerjaan'] = $item->done . ' / ' .$item->total;

                if ($item->complaint->isEmpty()) {
                    $status = 1;
                } elseif ($item->complaint->where('id_pm_approval', null)->isNotEmpty() && $item->complaint->where('id_pm_approval', null)->isNotEmpty()) {
                    $status = 2;
                } else {
                    $status = 3;
                }

                $item->status_project = $status;
            }

            return response()->json(['success' => true, 'message' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function detailBOD(Request $request)
    {
        try{                  
            $data = OnRequest::with(['complaint','complaint.vendors:id,name','customer:id,name','pm.karyawan:id,name,nomor_telpon','pa.karyawan:id,name,nomor_telpon','pe.karyawan:id,name,nomor_telpon','pe2.karyawan:id,name,nomor_telpon','lokasi:id,name'])
                        ->where('id',$request->id)
                        ->first();
         
            return response()->json(['success' => true, 'message' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function navbarBOD(Request $request)
    {
        try{
            $data = ProjectPekerjaan::select('id','id_project','id_kategori','status')
                    ->with(['projects'])->where('id_project',$request->id)
                    ->first();

            $vendor = Keluhan::where('on_request_id',$request->id)
                    ->whereNotNull(['id_pm_approval','id_bod_approval'])
                    ->select('id_vendor')
                    ->groupBy('id_vendor')
                    ->get();
                
            $kategori = Kategori::get();

            $progress = ProjectPekerjaan::where('id_project', $request->id)
                        ->select('id_kategori')
                        ->selectRaw('COUNT(id_pekerjaan) as total_status_1')
                        ->selectRaw('SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as total_status_2')
                        ->groupBy('id_kategori')
                        ->get();
            
            $progressByKategori = [];
            
            foreach ($progress as $item) {
                $progressByKategori[$item->id_kategori] = [
                    'total_status_1' => $item->total_status_1,
                    'total_status_2' => $item->total_status_2,
                ];
            }
            
            foreach ($kategori as $item) {
                $kategoriProgress = $progressByKategori[$item->id] ?? [
                    'total_status_1' => 0,
                    'total_status_2' => 0,
                ];
            
                $item->progress = $kategoriProgress['total_status_2'] . ' / ' . $kategoriProgress['total_status_1'];
            }
                    
            $data['name'] = $data->projects->nama_project ?? '';
            $data['vendor'] = count($vendor);
            $data['kategori'] = $kategori;

            return response()->json(['success' => true, 'message' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function subkategoriBOD(Request $request)
    {
        try{
            $name = SubKategori::where('id_kategori', $request->id_kategori)->get();
            $namakategori = $name->first()->kategori->name ?? '';            

            // $progress = ProjectPekerjaan::with(['subkategori:id,name,id_kategori'])
            //     ->select('id','status','id_kategori','id_subkategori','id_project','created_at','updated_at','deskripsi_subkategori')
            //     ->where('id_project', $request->id_project)
            //     ->where('id_kategori', $request->id_kategori)
            //     ->filter($request)
            //     ->get();

            // $progress = ProjectPekerjaan::select('project_pekerjaan.id_subkategori', 'project_pekerjaan.deskripsi_subkategori', 'project_pekerjaan.status', 'project_pekerjaan.id_subkategori', 'sub_kategori.name')
            //             ->join('sub_kategori', 'project_pekerjaan.id_subkategori', '=', 'sub_kategori.id')
            //             ->where('project_pekerjaan.id_project', $request->id_project)
            //             ->where('project_pekerjaan.id_kategori', $request->id_kategori)
            //             ->groupBy('project_pekerjaan.id_subkategori', 'project_pekerjaan.deskripsi_subkategori', 'project_pekerjaan.status', 'project_pekerjaan.id_subkategori', 'sub_kategori.name')
            //             ->filter($request)
            //             ->get();
            $progress = ProjectPekerjaan::select('project_pekerjaan.deskripsi_subkategori', 'sub_kategori.name', DB::raw('count(project_pekerjaan.id_pekerjaan) as total'),'project_pekerjaan.id_subkategori', 'project_pekerjaan.status','project_pekerjaan.id_project','project_pekerjaan.deskripsi_subkategori')
                    ->join('sub_kategori', 'project_pekerjaan.id_subkategori', '=', 'sub_kategori.id')
                    ->where('project_pekerjaan.id_project', $request->id_project)
                    ->where('project_pekerjaan.id_kategori', $request->id_kategori)
                    ->groupBy('sub_kategori.name', 'project_pekerjaan.deskripsi_subkategori','project_pekerjaan.id_subkategori', 'project_pekerjaan.status','project_pekerjaan.id_project')
                    ->filter($request)
                    ->get();
        
            foreach ($progress as $item) {
                $item->name = ($item->subkategori->name ?? '') . " " . ($item->deskripsi_subkategori ?? '');
        
                if ($item->status == 1) {
                    $status = '';
                } elseif ($item->status == 2) {
                    $status = 'Proses';
                } elseif ($item->status == 3) {
                    $status = 'Done';
                }
        
                $item->status = $status;
            }
            // $name = SubKategori::where('id_kategori', $request->id_kategori)->get();
            // $namakategori = $name->first()->kategori->name ?? '';            

            // $progress = ProjectPekerjaan::where('id_project', $request->id_project)
            //     ->where('id_kategori', $request->id_kategori)
            //     ->select('id_subkategori', DB::raw('MAX(status) as max_status'))
            //     ->groupBy('id_subkategori')
            //     ->filter($request)
            //     ->get();

            // $subkategoriIds = $progress->pluck('id_subkategori')->toArray();

            // $subkategori = SubKategori::where('id_kategori', $request->id_kategori)
            //     ->whereIn('id', $subkategoriIds)
            //     ->get();

            // foreach ($subkategori as $item) {
            //     $status = '';

            //     $matchingProgress = $progress->firstWhere('id_subkategori', $item->id);

            //     if ($matchingProgress) {
            //         $maxStatus = $matchingProgress->max_status;

            //         if ($maxStatus == 1) {
            //             $status = '';
            //         } elseif ($maxStatus == 2) {
            //             $status = 'Proses';
            //         } elseif ($maxStatus == 3) {
            //             $status = 'Done';
            //         }
            //     }

            //     $item->status = $status;
            // }
         
            return response()->json(['success' => true, 'message' => 'success', 'namakategori' => $namakategori , 'subkategori' => $progress]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function pekerjaanBOD(Request $request)
    {
        try{
            $beforePhoto = BeforePhoto::where('id_project',$request->id_project)
                            ->where('id_subkategori',$request->id_subkategori)
                            ->where('id_kategori',$request->id_kategori)
                            ->get();
            $afterPhoto = AfterPhoto::where('id_project',$request->id_project)
                            ->where('id_subkategori',$request->id_subkategori)
                            ->where('id_kategori',$request->id_kategori)
                            ->get();

            $kategori = SubKategori::find($request->id_subkategori);   

            $data = ProjectPekerjaan::with('vendors:id,name')->select('id','id_pekerjaan','id_vendor','length','unit','status','deskripsi_pekerjaan')
                    ->where('id_project', $request->id_project)
                    ->where('id_subkategori', $request->id_subkategori)
                    ->where('id_kategori',$request->id_kategori)
                    ->where('deskripsi_subkategori',$request->deskripsi_subkategori)
                    ->orderBy('created_at','desc')
                    ->limit(3)
                    ->get();

            foreach ($data as $item) {
                $item['nama_pekerjaan'] = ($item->pekerjaan->name ?? '') . ' ' . ($item->deskripsi_pekerjaan ?? '');
                $item['nama_vendor'] = $item->vendors->name ?? '';
                $item['ukuran'] = $item->length ." ". $item->unit;
            }
         
            return response()->json(['success' => true, 'message' => 'success', 'kategori' => $kategori->kategori->name ,'subkategori' => $kategori->name , 'data' => $data, 'before' => $beforePhoto, 'after' => $afterPhoto]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function detailpekerjaanBOD(Request $request)
    {
        try{
            $data = ProjectPekerjaan::with('pekerjaan:id,name')->select('id','id_pekerjaan','id_vendor','length','unit','status','deskripsi_pekerjaan')
                    ->where('id_project', $request->id_project)
                    ->where('id_subkategori', $request->id_subkategori)
                    ->where('id_kategori',$request->id_kategori)
                    ->where('deskripsi_subkategori',$request->deskripsi_subkategori)
                    ->orderBy('created_at','desc')
                    ->get();

            foreach($data as $value){
                $value['nama_pekerjaan'] = ($value->pekerjaan->name ?? '') . ' ' . ($value->deskripsi_pekerjaan ?? '');
            }

            return response()->json(['success' => true, 'message' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
