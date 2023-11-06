<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OnRequest;
use App\Models\Customer;
use App\Models\Vendor;

class DashboardController extends Controller
{
    public function index()
    {
        $spkrequest = OnRequest::with(['keluhan'])->whereHas('keluhan',function($query){
                        $query->whereNull(['id_pm_approval','id_bod_approval']);
                    })->get();
        $spkrequest = count($spkrequest);
        $onprogress = OnRequest::with(['keluhan'])->whereHas('keluhan',function($query){
            $query->whereNotNull(['id_pm_approval','id_bod_approval']);
        })->get();
        $onprogress = count($onprogress);
        $complete = count(OnRequest::where('status',1)->get());
        $totalcustomer = count(Customer::get());
        $totalvendor = count(Vendor::get());

        return view('dashboard',compact('spkrequest','onprogress','complete','totalcustomer','totalvendor'));
    }
}
