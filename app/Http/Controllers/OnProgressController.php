<?php

namespace App\Http\Controllers;

use App\Models\OnRequest;
use App\Models\Pekerjaan;
use App\Models\Vendor;
use Illuminate\Http\Request;

class OnProgressController extends Controller
{
    public function index()
    {
        $data = OnRequest::get();
        return view('on_progres.index',compact('data'));
    }

    public function edit($id)
    {
        $data = OnRequest::find($id);
        return view('on_progres.edit',compact('data'));
    }

    public function addWork($id)
    {
        $works = Pekerjaan::all();
        $vendors = Vendor::all();
        return view('on_progres.request',compact('id','works','vendors'));
    }

    public function requestPost(Request $request)
    {
        dd($request->all());
    }

    public function detailWorker()
    {
        return view('on_progres.detail');
    }

    public function subDetailWorker()
    {
        return view('on_progres.detail-work');
    }
}
