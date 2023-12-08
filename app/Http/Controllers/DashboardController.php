<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OnRequest;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\Keluhan;
use App\Models\ProjectPekerjaan;
use App\Models\ProjectManager;
use App\Models\ProjectAdmin;
use Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $cekRole = Auth::user()->role->name;
        $cekId = Auth::user()->id_karyawan;
        $cekPm = ProjectAdmin::where('id_karyawan',$cekId)->first();
        $cekPa  = ProjectManager::where('id_karyawan', $cekId)->first();
        $result = ProjectManager::get()->toArray();

        $spkrequest = OnRequest::with(['kapal', 'customer']);

        if ($cekRole == 'Project Manager') {
            $spkrequest->where('pm_id', $cekPa->id ?? '');
        }else if ($cekRole == 'BOD') {
            if($result){
                $spkrequest->whereIn('pm_id', array_column($result, 'id'));
            }
        }else{
            $spkrequest->where('pm_id', '');
        }

        $spkrequest = $spkrequest->whereHas('complaint', function ($query) use ($cekRole) {
            if ($cekRole == 'Project Manager') {
                $query->whereNull('id_pm_approval')->whereNull('id_bod_approval');
            } elseif ($cekRole == 'BOD') {
                $query->whereNotNull('id_pm_approval')->whereNull('id_bod_approval');
            }
        })->get();

        $pekerjaan = OnRequest::whereNotNull(['approval_pm'])->get();

        $keluhan = $spkrequest->map(function ($item) use ($cekRole) {
            $jumlahKeluhan = $item->complaint->filter(function ($complaint) use ($cekRole) {
                if ($cekRole == 'Project Manager') {
                    return is_null($complaint->id_pm_approval) && is_null($complaint->id_bod_approval);
                } elseif ($cekRole == 'BOD') {
                    return !is_null($complaint->id_pm_approval) && is_null($complaint->id_bod_approval);
                }
                return false;
            })->count();

            return [
                'id' => $item->id,
                'code' => $item->code,
                'nama_project' => $item->nama_project,
                'jumlah' => $jumlahKeluhan,
            ];
        })->toArray();

        $spkrequest = count($spkrequest);

        $onprogress =   OnRequest::with(['pm','pm.karyawan','customer'])
                        ->whereHas('keluhan',function($query){
                            $query->whereNotNull(['id_pm_approval','id_bod_approval']);
                        })
                        ->where('status',1)
                        ->get();

        $onprogress = count($onprogress);
        $complete = count(OnRequest::where('status',2)->get());

        $totalcustomer = count(Customer::get());
        $totalvendor = count(Vendor::get());

        $progress = ProjectPekerjaan::whereNotNull('id_pekerjaan')
                    ->select('id_vendor')
                    ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as onprogress')
                    ->selectRaw('SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as complete')
                    ->groupBy('id_vendor')
                    ->orderByDesc('complete')
                    ->get();

        $pm = ProjectManager::with(['projects' => function ($query) {
                $query->select('pm_id')
                    ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as onprogress')
                    ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as complete')
                    ->groupBy('pm_id');
            }])
            ->selectRaw('pm.*, (SELECT SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) FROM project WHERE project.pm_id = pm.id) as complete')
            ->orderByDesc('complete')
            ->get();

        $data       = OnRequest::orderBy('created_at','desc')->get();

        return view('dashboard',compact('keluhan','spkrequest','onprogress','complete','totalcustomer','totalvendor','data','progress','pm','pekerjaan'));
    }
}
