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
use App\Models\Keluhan;
use Carbon\Carbon;
use DB;

class ProjectManagerController extends Controller
{
    public function index(Request $request)
    {
        try{
            $data = OnRequest::with(['complaint','customer:id,name'])
                ->select('A.nama_project', 'A.created_at', 'A.id_customer',
                    DB::raw('(SELECT COUNT(id_Pekerjaan) FROM project_pekerjaan WHERE status = 3 AND id_project = A.id) AS done'), 
                    DB::raw('(SELECT COUNT(id_Pekerjaan) FROM project_pekerjaan WHERE id_project = A.id) AS total'), 'A.status')
                ->from('project as A')
                ->leftJoin('project_pekerjaan as b', 'A.id', '=', 'b.id_project')
                ->where('A.pm_id', $request->pm_id)
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

    public function detailPM(Request $request)
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

    public function approve(Request $request)
    {
        $data   = Keluhan::find($request->id);     

        if($request->type == 'PM')
        {
            if($data->id_pm_approval == null)
            {
                $data->id_pm_approval   = $request->id_user;
                $data->pm_date_approval = Carbon::now();
            }else{
                return response()->json(['status' => 500, 'message' => 'PM Sudah Approve']);
            }
        }
        else{
            if($data->id_bod_approval == null)
            {
                $data->id_bod_approval  = $request->id_user;
                $data->bod_date_approval    = Carbon::now();
            }else{
                return response()->json(['status' => 500, 'message' => 'BOD Sudah Approve']);
            }
        }
        $data->save();

        return response()->json(['status' => 200, 'message' => 'Berhasil Di Approve']);
    }

    public function navbarPM(Request $request)
    {
        try{
            $data = ProjectPekerjaan::select('id','id_project','id_kategori','status')
                    ->with(['projects'])->where('id_project',$request->id)
                    ->first();

            // $vendor = ProjectPekerjaan::where('id_project',$request->id)
            //         ->get();
            
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

    public function subkategoriPM(Request $request)
    {
        try{
            $name = SubKategori::where('id_kategori', $request->id_kategori)->get();
            $namakategori = $name->first()->kategori->name ?? '';            

            // $progress = ProjectPekerjaan::select('project_pekerjaan.id', 'project_pekerjaan.deskripsi_subkategori', 'project_pekerjaan.status', 'project_pekerjaan.id_subkategori', 'sub_kategori.name')
            //             ->join('sub_kategori', 'project_pekerjaan.id_subkategori', '=', 'sub_kategori.id')
            //             ->where('project_pekerjaan.id_project', $request->id_project)
            //             ->where('project_pekerjaan.id_kategori', $request->id_kategori)
            //             ->groupBy('project_pekerjaan.id', 'project_pekerjaan.deskripsi_subkategori', 'project_pekerjaan.status', 'project_pekerjaan.id_subkategori', 'sub_kategori.name')
            //             ->filter($request)
            //             ->get();

            $progress = ProjectPekerjaan::select('project_pekerjaan.deskripsi_subkategori', 'sub_kategori.name', DB::raw('count(project_pekerjaan.id_pekerjaan) as total'),'project_pekerjaan.id_subkategori', 'project_pekerjaan.status','project_pekerjaan.id_project')
                    ->join('sub_kategori', 'project_pekerjaan.id_subkategori', '=', 'sub_kategori.id')
                    ->where('project_pekerjaan.id_project', $request->id_project)
                    ->where('project_pekerjaan.id_kategori', $request->id_kategori)
                    ->groupBy('sub_kategori.name', 'project_pekerjaan.deskripsi_subkategori','project_pekerjaan.id_subkategori', 'project_pekerjaan.status','project_pekerjaan.id_project')
                    ->filter($request)
                    ->get();
                
            foreach ($progress as $item) {
                $item->setAttribute('name', $item->subkategori->name . " " . $item->deskripsi_subkategori);
        
                if ($item->status == 1) {
                    $status = '';
                } elseif ($item->status == 2) {
                    $status = 'Proses';
                } elseif ($item->status == 3) {
                    $status = 'Done';
                }
        
                $item->status = $status;
            }
         
            return response()->json(['success' => true, 'message' => 'success', 'namakategori' => $namakategori , 'subkategori' => $progress]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function pekerjaanPM(Request $request)
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

            $data = ProjectPekerjaan::with('vendors:id,name')->select('id','id_pekerjaan','id_vendor','length','unit','status','deskripsi_pekerjaan','project_pekerjaan.deskripsi_subkategori')
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

    public function detailpekerjaanPM(Request $request)
    {
        try{
            $data = ProjectPekerjaan::with('pekerjaan:id,name')->select('id','id_pekerjaan','deskripsi_pekerjaan')
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

    // public function detailpekerjaanPM(Request $request)
    // {
    //     try{           
    //         $data = ProjectPekerjaan::find($request->id);
    //         $pekerjaan = Pekerjaan::find($data->id_pekerjaan);
    //         $subkategori = SubKategori::find($data->id_subkategori);
    //         $kategori = Kategori::find($data->id_kategori);
    //         $beforePhoto = BeforePhoto::where('id_project_pekerjaan',$request->id)->get();
    //         $afterPhoto = AfterPhoto::where('id_project_pekerjaan',$request->id)->get();

    //         $data['nama_pekerjaan'] = $data->pekerjaan->name ?? '';
    //         $data['nama_vendor'] = $data->vendors->name ?? '';
    //         $data['ukuran'] = $data->length ." ". $data->unit;
         
    //         return response()->json(['success' => true, 'message' => 'success', 'kategori' => $kategori->name, 'subkategori' => $subkategori->name, 'pekerjaan' => $pekerjaan->name, 'data' => $data, 'before' => $beforePhoto, 'after' => $afterPhoto]);
    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    //     }
    // }
}

