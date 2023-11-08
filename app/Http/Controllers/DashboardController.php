<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OnRequest;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\Keluhan;

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

        $data = OnRequest::get();

        return view('dashboard',compact('spkrequest','onprogress','complete','totalcustomer','totalvendor','data'));
    }
}
