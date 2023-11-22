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
use DB;
use Illuminate\Support\Facades\File;

class ProjectEngineerController extends Controller
{
    public function index(Request $request)
    {
        try{
            $data = OnRequest::with(['progress:id,id_project,id_vendor','progress.vendors:id,name','customer:id,name'])
                    ->select('id','nama_project','created_at','id_customer')
                    ->where('pe_id_1',$request->pe_id)
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

    public function detailPE(Request $request)
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

    public function navbarPE(Request $request)
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

    public function subkategoriPE(Request $request)
    {
        try{
            $subkategori = SubKategori::where('id_kategori', $request->id_kategori)->get();
            $namakategori = $subkategori->first()->kategori->name ?? '';            

            $progress = ProjectPekerjaan::where('id_project', $request->id_project)
                        ->where('id_kategori', $request->id_kategori)
                        ->where('id_subkategori', $request->id_subkategori)
                        ->select('id_subkategori', DB::raw('MAX(status) as max_status'))
                        ->groupBy('id_subkategori')
                        ->get();
            
            foreach ($subkategori as $item) {
                $status = ''; 

                $matchingProgress = $progress->firstWhere('id_subkategori', $item->id);
            
                if ($matchingProgress) {
                    $maxStatus = $matchingProgress->max_status;
            
                    if ($maxStatus == 1) {
                        $status = '';
                    } elseif ($maxStatus == 2) {
                        $status = 'Proses';
                    } elseif ($maxStatus == 3) {
                        $status = 'Done';
                    }
                }
            
                $item->status = $status;
            }
         
            return response()->json(['success' => true, 'message' => 'success', 'namakategori' => $namakategori , 'subkategori' => $subkategori]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function pekerjaanPE(Request $request)
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
                    ->where('id_project', $request->id_project)->where('id_subkategori', $request->id_subkategori)
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

    public function addPhoto(Request $request){
        $status_pekerjaan = $request->status_pekerjaan;
        $beforeFiles = $request->file('before');
        $afterFiles = $request->file('after');

        $projectPekerjaan = ProjectPekerjaan::where('id_project', $request->id_project)
                            ->where('id_subkategori', $request->id_subkategori)->where('id_kategori', $request->id_kategori)
                            ->get();
        // $projectPekerjaan->status = $status_pekerjaan;
        // $projectPekerjaan->save();

            // Memastikan $projectPekerjaan bukanlah koleksi kosong
    if ($projectPekerjaan->isNotEmpty()) {
        // Loop melalui setiap baris dan mengupdate status
        foreach ($projectPekerjaan as $pekerjaan) {
            $pekerjaan->status = $status_pekerjaan;
            $pekerjaan->save();
        }
    } else {
        // Handle jika tidak ada baris yang ditemukan
        // Misalnya, Anda bisa melemparkan exception atau memberikan pesan kesalahan
        // tergantung pada kebutuhan aplikasi Anda.
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
                $befores->id_kategori = $request->id_kategori;
                $befores->id_subkategori = $request->id_subkategori;
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
                $afters->id_kategori = $request->id_kategori;
                $afters->id_subkategori = $request->id_subkategori;
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
            $data = ProjectPekerjaan::with('pekerjaan:id,name')->select('id','id_pekerjaan','deskripsi_pekerjaan')
                    ->where('id',$request->id)->where('id_project', $request->id_project)->where('id_subkategori', $request->id_subkategori)
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
