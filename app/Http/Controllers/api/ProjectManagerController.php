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

            $data['nama_pekerjaan'] = $data->pekerjaan->name ?? '';
            $data['nama_vendor'] = $data->vendors->name ?? '';
            $data['ukuran'] = $data->length ." ". $data->unit;
         
            return response()->json(['success' => true, 'message' => 'success', 'kategori' => $kategori->name, 'subkategori' => $subkategori->name, 'pekerjaan' => $pekerjaan->name, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

