<?php

namespace App\Http\Controllers;

use App\Models\OnRequest;
use Illuminate\Http\Request;

class CompleteController extends Controller
{
    public function index()
    {
        $data = OnRequest::all();
        return view('complete.index',compact('data'));
    }

    public function edit($id)
    {
        $data = OnRequest::find($id);
        return view('complete.edit',compact('data'));
    }
}
