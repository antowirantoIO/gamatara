<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OnRequest;
use App\Models\ProjectPekerjaan;
use App\Models\Kategori;
use App\Models\SubKategori;
use App\Models\Pekerjaan;
use App\Models\SettingPekerjaan;
use App\Models\BeforePhoto;
use App\Models\AfterPhoto;

class ProjectManagerController extends Controller
{
    public function index(Request $request)
    {
        try{
            $project = OnRequest::filter($request)
                        ->where('pm_id',$request->pm_id)
                        ->where('status',1)
                        ->get();

            $projectIds[] = '';
            foreach ($project as $projectItem) {
                $projectIds[] = $projectItem->id;
            }

            $data = ProjectPekerjaan::with(['vendors:id,name'])->select('id','id_project','id_vendor')->whereIn('id_project', $projectIds)->get();

            foreach ($data as $item) {
                $item['progress'] = getProgresProject($item->id) . ' / ' . getCompleteProject($item->id);
                $item['nama_project'] = $item->projects->nama_project ?? '-';
                $item['nama_vendor'] = $item->vendors->name ?? '-';
                $item['tanggal'] = $item->projects->created_at ? date('d M Y', strtotime($item->projects->created_at)) : '-';
            }

            return response()->json(['success' => true, 'message' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function detailPM(Request $request)
    {
        try{
            $data = ProjectPekerjaan::with('vendors:id,name')->select('id','id_vendor','id_project')->where('id',$request->id)->first();
                  
            $requests = OnRequest::with(['complaint','complaint.vendors:id,name'])
                        ->where('id',$data->id_project)
                        ->first();

            $pekerjaan = ProjectPekerjaan::where('id_project',$data->id_project)
                        ->whereNotNull('id_pekerjaan')
                        ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
                        ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
                        ->first();

            $vendor = ProjectPekerjaan::where('id_project',$data->id_project)
                    ->select('id_vendor')
                    ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
                    ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
                    ->groupBy('status', 'id_vendor')
                    ->get();

            if($pekerjaan){
                $pekerjaan = [
                'total_status_1' => $pekerjaan->total_status_1,
                'total_status_2' => $pekerjaan->total_status_2
                ];
            }else{
                $pekerjaan = [
                    'total_status_1' => "0",
                    'total_status_2' => "0"
                    ];
            }

            $data['projects'] = $data->projects ?? '';
             
            if ($requests) {
                $data['request'] = $requests->complaint;
                $data['project_manajer'] = $data->projects->pm->karyawan->name ?? null;
                $data['project_admin'] = $data->projects->pa->karyawan->name ?? null;
                $data['project_engineer_1'] = $data->projects->pe->karyawan->name ?? null;
                $data['project_engineer_2'] = $data->projects->pe2->karyawan->name ?? null;
                $data['lokasi_project'] = $data->projects->lokasi->name ?? '';
            } else {
                $data['request'] = null;
            }

            $data['nama_vendor'] = $data->vendors->name ?? '-';
            $data['pekerjaan'] = $pekerjaan;
            $data['total_vendor'] = count($vendor);
         
            return response()->json(['success' => true, 'message' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function approvePM(Request $request)
    {

    }

    public function approveBOD(Request $request)
    {
        
    }

    public function navbarPM(Request $request)
    {
        try{
            $data = ProjectPekerjaan::select('id','id_project','id_vendor','id_kategori','status')
                    ->with(['projects'])->where('id',$request->id)
                    ->first();

            $vendor = ProjectPekerjaan::where('id',$request->id)
                    ->select('id_vendor')
                    ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
                    ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
                    ->groupBy('status', 'id_vendor')
                    ->get();

            $kategori = Kategori::get();

            $progress = ProjectPekerjaan::where('id', $request->id)
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

    public function subkategoriPM(Request $request)
    {
        try{
            $subkategori = SubKategori::where('id_kategori', $request->id)->get();
            $namakategori = $subkategori->first()->kategori->name ?? '';            

            $progress = ProjectPekerjaan::where('id_project', $request->id)
                ->select('id_subkategori')
                ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
                ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
                ->groupBy('id_subkategori')
                ->get();
            
            $progressBySubkategori = [];
            
            foreach ($progress as $item) {
                $progressBySubkategori[$item->id_subkategori] = [
                    'total_status_1' => $item->total_status_1,
                    'total_status_2' => $item->total_status_2,
                ];
            }
            
            foreach ($subkategori as $item) {
                $subkategoriProgress = $progressBySubkategori[$item->id] ?? [
                    'total_status_1' => 0,
                    'total_status_2' => 0,
                ];
            
                $item->progress = $subkategoriProgress['total_status_2'] . ' / ' . $subkategoriProgress['total_status_1'];
            }
         
            return response()->json(['success' => true, 'message' => 'success', 'namakategori' => $namakategori , 'subkategori' => $subkategori]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function pekerjaanPM(Request $request)
    {
        try{
            $pekerjaan = SettingPekerjaan::where('id_sub_kategori', $request->id)->get();
            $subkategori = $pekerjaan->first()->subkategori->name ?? '';
            $kategori = SubKategori::find($request->id);   

            $data = ProjectPekerjaan::select('id','id_pekerjaan','id_vendor','length','unit','status')
                    ->where('id_subkategori', $request->id)
                    ->get();

            foreach ($data as $item) {
                if ($item->status == 1) {
                    $item->status = 'proses';
                } elseif ($item->status == 2) {
                    $item->status = 'done';
                }
                $item['nama_pekerjaan'] = $item->pekerjaan->name ?? '';
                $item['nama_vendor'] = $item->vendors->name ?? '';
                $item['ukuran'] = $item->length ." ". $item->unit;
            }
         
            return response()->json(['success' => true, 'message' => 'success', 'kategori' => $kategori->kategori->name ,'subkategori' => $subkategori , 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function detailpekerjaanPM(Request $request)
    {
        try{           
            $data = ProjectPekerjaan::find($request->id);
            $pekerjaan = Pekerjaan::find($data->id_pekerjaan);
            $subkategori = SubKategori::find($data->id_subkategori);
            $kategori = Kategori::find($data->id_kategori);
            $beforePhoto = BeforePhoto::where('id_project_pekerjaan',$request->id)->get();
            $afterPhoto = AfterPhoto::where('id_project_pekerjaan',$request->id)->get();

            $data['nama_pekerjaan'] = $data->pekerjaan->name ?? '';
            $data['nama_vendor'] = $data->vendors->name ?? '';
            $data['ukuran'] = $data->length ." ". $data->unit;
         
            return response()->json(['success' => true, 'message' => 'success', 'kategori' => $kategori->name, 'subkategori' => $subkategori->name, 'pekerjaan' => $pekerjaan->name, 'data' => $data, 'before' => $beforePhoto, 'after' => $afterPhoto]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

