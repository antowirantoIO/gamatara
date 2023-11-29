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
use DB;
use Illuminate\Support\Facades\File;

class ProjectEngineerController extends Controller
{
    public function index(Request $request)
    {
        try{
            $data = OnRequest::with(['complaint','customer:id,name'])
                ->select('A.nama_project', 'A.created_at', 'A.id','A.id_customer',
                    DB::raw('(SELECT COUNT(id_Pekerjaan) FROM project_pekerjaan WHERE status = 3 AND id_project = A.id) AS done'), 
                    DB::raw('(SELECT COUNT(id_Pekerjaan) FROM project_pekerjaan WHERE id_project = A.id) AS total'), 'A.status')
                ->from('Project as A')
                ->leftJoin('project_pekerjaan as b', 'A.id', '=', 'b.id_project')
                ->where('A.pe_id_1', $request->pe_id)
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

    public function detailPE(Request $request)
    {
        try{                  
            $data = OnRequest::with(['complaint','complaint.vendors:id,name','customer:id,name','pm.karyawan:id,name,nomor_telpon','pm.pas.karyawan:id,name,nomor_telpon','pm.pes.karyawan:id,name,nomor_telpon','pe2.karyawan:id,name,nomor_telpon','lokasi:id,name'])
                        ->where('id',$request->id)
                        ->first();
         
            return response()->json(['success' => true, 'message' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function navbarPE(Request $request)
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

    public function subkategoriPE(Request $request)
    {
        try{
            $name = SubKategori::where('id_kategori', $request->id_kategori)->get();
            $namakategori = $name->first()->kategori->name ?? '';            

            $progress = ProjectPekerjaan::with(['vendors:id,name'])->select('project_pekerjaan.deskripsi_subkategori','project_pekerjaan.kode_unik', 'sub_kategori.name', DB::raw('count(project_pekerjaan.id_pekerjaan) as total'),'project_pekerjaan.id_subkategori', 'project_pekerjaan.status','project_pekerjaan.id_project','project_pekerjaan.deskripsi_subkategori','id_vendor')
                    ->join('sub_kategori', 'project_pekerjaan.id_subkategori', '=', 'sub_kategori.id')
                    ->where('project_pekerjaan.id_project', $request->id_project)
                    ->where('project_pekerjaan.id_kategori', $request->id_kategori)
                    ->groupBy('sub_kategori.name', 'project_pekerjaan.deskripsi_subkategori','project_pekerjaan.kode_unik','project_pekerjaan.id_subkategori', 'project_pekerjaan.status','project_pekerjaan.id_project','id_vendor')
                    ->filter($request)
                    ->get();
        
            foreach ($progress as $item) {
                $item->nama_vendor = $item->vendors->name ?? '';
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
         
            return response()->json(['success' => true, 'message' => 'success', 'namakategori' => $namakategori , 'subkategori' => $progress]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function pekerjaanPE(Request $request)
    {
        try{
            $beforePhoto = BeforePhoto::where('id_project',$request->id_project)
                            ->where('kode_unik',$request->kode_unik)
                            ->get();
            $afterPhoto = AfterPhoto::where('id_project',$request->id_project)
                            ->where('kode_unik',$request->kode_unik)
                            ->get();

            $kategori = SubKategori::find($request->id_subkategori);   
            $pekerjaan = ProjectPekerjaan::where('id_project', $request->id_project)
                        ->where('kode_unik', $request->kode_unik)
                        ->first();

            $data = ProjectPekerjaan::with('vendors:id,name')->select('id','id_pekerjaan','id_vendor','length','unit','status','deskripsi_pekerjaan','deskripsi_subkategori','kode_unik','length','width','thick','amount','unit')
                    ->where('id_project', $request->id_project)
                    ->where('kode_unik', $request->kode_unik)
                    ->orderBy('created_at','desc')
                    ->limit(3)
                    ->get();

            foreach ($data as $item) {
                $item['nama_pekerjaan'] = ($item->pekerjaan->name ?? '') . ' ' . ($item->deskripsi_pekerjaan ?? '') . ' ' . ($item->length ?? '') . ' ' . ($item->width ?? '') . ' ' . ($item->thick ?? '') . ' ' . ($item->qty ?? '') . ' ' . ($item->amount ?? '');
                $item['nama_vendor'] = $item->vendors->name ?? '';
                $item['ukuran'] = $item->length ." ". $item->unit;
            }
         
            return response()->json(['success' => true, 'message' => 'success', 'kategori' => $kategori->kategori->name ,'subkategori' => $kategori->name." ".$pekerjaan->deskripsi_subkategori , 'data' => $data, 'before' => $beforePhoto, 'after' => $afterPhoto]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function addPhoto(Request $request){
        $status_pekerjaan = $request->status_pekerjaan;
        $beforeFiles = $request->file('before');
        $afterFiles = $request->file('after');

        $projectPekerjaan = ProjectPekerjaan::where('id_project', $request->id_project)
                            ->where('kode_unik', $request->kode_unik)
                            ->get();

        if ($projectPekerjaan->isNotEmpty()) {
            foreach ($projectPekerjaan as $pekerjaan) {
                $pekerjaan->status = $status_pekerjaan;
                $pekerjaan->save();
            }
        } else {
 
        }

        if($request->file('before')){
            foreach ($beforeFiles as $before) {
                if ($before && $before->isValid()) {
                    $filename = 'before' . time() . rand(1, 9999) . '.' . $before->getClientOriginalExtension();
                    $destinationPath = 'uploads/images';
            
                    if (!File::isDirectory($destinationPath)) {
                        File::makeDirectory($destinationPath, 0755, true, true);
                    }
            
                    $before->move($destinationPath, $filename);
                    $destination =  $destinationPath . '/' . $filename;
                }

                $befores = new BeforePhoto();
                $befores->kode_unik = $request->kode_unik;
                $befores->id_project = $request->id_project;
                $befores->photo = $destinationPath . '/' . $filename;
                $befores->save();
            }
        }
    
        if($request->file('after')){
            foreach ($afterFiles as $after) {
                if ($after && $after->isValid()) {
                    $filename = 'after' . time() . rand(1, 9999) . '.' . $after->getClientOriginalExtension();
                    $destinationPath = 'uploads/images';
            
                    if (!File::isDirectory($destinationPath)) {
                        File::makeDirectory($destinationPath, 0755, true, true);
                    }
            
                    $after->move($destinationPath, $filename);
                    $destination =  $destinationPath . '/' . $filename;
                }
                $afters = new AfterPhoto();
                $afters->kode_unik = $request->kode_unik;
                $afters->id_project = $request->id_project;
                $afters->photo = $destinationPath . '/' . $filename;
                $afters->save();
            }
        }

        return response()->json(['success' => true, 'message' => 'success']);
    }

    public function detailpekerjaanPE(Request $request)
    {
        try{
            $data = ProjectPekerjaan::with('pekerjaan:id,name')->select('id','id_pekerjaan','id_vendor','length','unit','status','deskripsi_pekerjaan','kode_unit','length','width','thick','amount','unit')
                    ->where('id_project', $request->id_project)
                    ->where('kode_unik', $request->kode_unik)
                    ->orderBy('created_at','desc')
                    ->get();

            foreach($data as $value){
                $value['nama_pekerjaan'] = ($item->pekerjaan->name ?? '') . ' ' . ($item->deskripsi_pekerjaan ?? '') . ' ' . ($item->length ?? '') . ' ' . ($item->width ?? '') . ' ' . ($item->thick ?? '') . ' ' . ($item->qty ?? '') . ' ' . ($item->amount ?? '')  . ' ' . ($item->unit ?? '');
            }

            return response()->json(['success' => true, 'message' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
