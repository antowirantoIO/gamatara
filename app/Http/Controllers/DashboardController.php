<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OnRequest;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\Keluhan;
use App\Models\ProjectPekerjaan;

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
        $complete = count(OnRequest::where('status',1)->get());
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
                           
        $pm = ProjectPekerjaan::with('projects')
                                ->whereNotNull('id_pekerjaan')
                                ->select('id_project')
                                ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as onprogress')
                                ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as complete')
                                ->groupBy('id_project')
                                ->get();

        $data = OnRequest::get();

        return view('dashboard',compact('spkrequest','onprogress','complete','totalcustomer','totalvendor','data','vendors','progress','pm'));
    }
}
