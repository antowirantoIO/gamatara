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
            $spkrequest->where('pm_id', $cekPa->id);
        }else if ($cekRole == 'BOD' || $cekRole == 'Super Admin' || $cekRole == 'Administator') {
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
                                $query->whereNull('id_bod_approval')->whereNotNull('id_pm_approval');
                            }
                        })
                        ->get();
                    
        $keluhan = $spkrequest->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'nama_project' => $item->nama_project,
                'jumlah' => $item->complaint->count(),
            ];
        })->toArray();
            
        $spkrequest = count($spkrequest);

        $onprogress = OnRequest::whereHas('complaint',function($query){
                            $query->whereNotNull(['id_pm_approval','id_bod_approval']);
                        })
                        ->where('status',1)
                        ->get();
        $onprogress = count($onprogress);
        $complete = OnRequest::whereHas('progress', function ($query) {
            $query->whereNotNull('id_pekerjaan')->where('status', 2);
        })->count();
        
        $totalcustomer = count(Customer::get());
        $totalvendor = count(Vendor::get());

        $vendors = Keluhan::whereNotNull(['id_pm_approval','id_bod_approval'])
                    ->select('id_vendor')
                    ->groupBy('id_vendor')
                    ->get();

        $progress = ProjectPekerjaan::whereNotNull('id_pekerjaan')
                    ->select('id_vendor')
                    ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as onprogress')
                    ->selectRaw('SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as complete')
                    ->groupBy('id_vendor')
                    ->get();

        $pm = ProjectManager::with(['projects' => function ($query) {
            $query->select('pm_id')
                ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as onprogress')
                ->selectRaw('SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as complete')
                ->groupBy('pm_id');
        }])->get();                                        

        $data       = OnRequest::get();

        return view('dashboard',compact('keluhan','spkrequest','onprogress','complete','totalcustomer','totalvendor','data','vendors','progress','pm'));
    }
}
