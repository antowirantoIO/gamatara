<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OnRequest;
use App\Models\ProjectPekerjaan;
use App\Models\Kategori;

class ProjectManagerController extends Controller
{
    public function index(Request $request)
    {
        try{
            $data = OnRequest::with(['kapal','customer'])
                        ->filter($request)
                        ->where('pm_id',$request->pm_id)
                        ->where('status',1)
                        ->get();

            foreach ($data as $item) {
                $item['progress'] = getProgresProject($item->id) . ' / ' . getCompleteProject($item->id);
                // Lakukan sesuatu dengan nilai $progress
            }
            

            return response()->json(['success' => true, 'message' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function detailPM(Request $request)
    {
        try{
            $data = OnRequest::with(['complaint'])->where('id',$request->id)
                    ->first();

            $pekerjaan = ProjectPekerjaan::where('id_project',$request->id)
                            ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
                            ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
                            ->first();

            $vendor = ProjectPekerjaan::where('id_project',$request->id)
                ->select('id_vendor')
                ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
                ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
                ->groupBy('status', 'id_vendor')
                ->get();

            if($pekerjaan->total_status_1){
                $pekerjaan = $pekerjaan->total_status_2 / $pekerjaan->total_status_1;
            }else{
                $pekerjaan = '0 / 0';
            }

            $data['project_manajer'] = $data->pm->karyawan->name ?? null;
            $data['pekerjaan'] = $pekerjaan;
            $data['vendor'] = count($vendor);

            foreach($data->pm->pe as $value){
                $value['nama_karyawan'] =  $value->karyawan->name;
            } 
         
            return response()->json(['success' => true, 'message' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function navbarPM(Request $request)
    {
        try{
            $data = OnRequest::where('id',$request->id)
                    ->select('nama_project')
                    ->first();

            $vendor = ProjectPekerjaan::where('id_project',$request->id)
                    ->select('id_vendor')
                    ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
                    ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
                    ->groupBy('status', 'id_vendor')
                    ->get();

            $kategori = Kategori::get();

            $progress = ProjectPekerjaan::where('id_project', $request->id)
                ->select('id_kategori')
                ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
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
                    
            $data['vendor'] = count($vendor);
         
            return response()->json(['success' => true, 'message' => 'success', 'data' => $data, 'kategori' => $kategori]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

