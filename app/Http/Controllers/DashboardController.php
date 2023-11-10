<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OnRequest;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\Keluhan;
use App\Models\ProjectPekerjaan;
use App\Models\ProjectManager;

class DashboardController extends Controller
{
    public function index()
    {
        $spkrequest = Keluhan::whereNull(['id_pm_approval','id_bod_approval'])->get();
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
                    ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as onprogress')
                    ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as complete')
                    ->groupBy('id_vendor')
                    ->get();

        $pm = ProjectManager::with(['projects', 'projects.progress'])
                ->select('pm.*', \DB::raw('SUM(CASE WHEN project_pekerjaan.status = 1 THEN 1 ELSE 0 END) as onprogress'))
                ->selectRaw('SUM(CASE WHEN project_pekerjaan.status = 2 THEN 1 ELSE 0 END) as complete')
                ->leftJoin('project', 'project.pm_id', '=', 'pm.id')
                ->leftJoin('project_pekerjaan', 'project.id', '=', 'project_pekerjaan.id_project')
                ->whereNotNull('project_pekerjaan.id_pekerjaan')
                ->groupBy('pm.id')
                ->get();               

        $data = OnRequest::get();

        return view('dashboard',compact('spkrequest','onprogress','complete','totalcustomer','totalvendor','data','vendors','progress','pm'));
    }
}
