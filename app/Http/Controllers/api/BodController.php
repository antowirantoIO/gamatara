<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
use DB;

class BodController extends Controller
{
    public function laporanCustomer(Request $request)
    {
        try{
            $data = Customer::select('id','name')->has('projects')->with('projects','projects.progress:harga_customer,qty,id_project')->get();
        
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
                                $jumlah_tagihan += $progress->harga_customer * $progress->qty;
                            }
                        }
                    }
                }
            
                $value['jumlah_tagihan'] = 'Rp '. number_format($jumlah_tagihan, 0, ',', '.');
            }

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

            $arrayChart = json_encode($totalHargaPerBulan, JSON_NUMERIC_CHECK);

            return response()->json(['success' => true, 'message' => 'success', 'data' => $data,'chart'=> $arrayChart]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function laporanVendor(Request $request)
    {
        try{
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

            if($request->tahun != null)
            {
                $tahun = $request->tahun;
            }else{
                $tahun = now()->format('Y');
            }

            $byTonase = DB::table('vendor as A')
                    ->join('project_pekerjaan as B', 'A.id', '=', 'B.id_vendor')
                    ->join('Project as C', 'B.id_project', '=', 'C.id')
                    ->select('A.id', 'A.name', DB::raw('SUM(B.amount) as tonase'))
                    ->whereYear('C.created_at', $tahun)
                    ->groupBy('A.id')
                    ->orderByDesc(DB::raw('SUM(B.amount)'))
                    ->get();

            $byVolume = DB::table('vendor as A')
                        ->join('project_pekerjaan as B', 'A.id', '=', 'B.id_vendor')
                        ->join('project as C', 'B.id_project', '=', 'C.id')
                        ->select('A.id', 'A.name', DB::raw('SUM(B.amount) as volume'))
                        ->whereYear('C.created_at', $tahun)
                        ->groupBy('A.id')
                        ->orderByDesc(DB::raw('SUM(B.amount)'))
                        ->get();

            return response()->json(['success' => true, 'message' => 'success', 'data' => $data, 'byTonase'=> $byTonase , 'byVolume' => $byVolume]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function laporanPM(Request $request)
    {
        try{
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
      
            $chart = OnRequest::select('pm_id', 'status','created_at')
                    ->with(['pm','pm.karyawan'])
                    ->whereYear('created_at',$tahun)
                    ->get();

            $chartData = $chart->groupBy('pm_id')->map(function (&$groupedData) {
                $onProgressCount = $groupedData->where('status', 1)->count();
                $completeCount = $groupedData->where('status', 2)->count();

                $employeeName = $groupedData->first()->pm->karyawan->name;

                return [
                    'name' => $employeeName,
                    'on_progress' => $onProgressCount,
                    'complete' => $completeCount,
                ];

            });

            return response()->json(['success' => true, 'message' => 'success', 'data' => $data ,'chart' => $chartData]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function index(Request $request)
    {
        try{
            $data = OnRequest::has('progress')->with(['progress:id,id_project,id_vendor','progress.vendors:id,name','customer:id,name'])
                    ->select('id','nama_project','created_at','id_customer')
                    ->where('status',1)
                    ->get();

            foreach ($data as $item) {
                $item['nama_customer'] = $item->customer->name ?? '';
                $item['tanggal'] = $item->created_at ? date('d M Y', strtotime($item->created_at)) : '-';
                $item['progress_pekerjaan'] = getProgresProject($item->id) . ' / ' . getCompleteProject($item->id);

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

            $vendor = ProjectPekerjaan::where('id_project',$request->id)
                    ->get();

            $kategori = Kategori::get();

            $progress = ProjectPekerjaan::where('id_project', $request->id)
                        ->select('id_kategori')
                        ->selectRaw('COUNT(id) as total_status_1')
                        ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
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
}
