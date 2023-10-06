<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OnRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $request = count(OnRequest::get());

        return view('dashboard',compact('request'));
    }
}
