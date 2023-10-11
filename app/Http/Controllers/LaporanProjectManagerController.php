<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\ExportLaporanVendor;
use App\Models\ProjectManager;

class LaporanProjectManagerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProjectManager::filter($request);

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('name', function($data){
                return $data->karyawan->name ?? '';
            })
            ->addColumn('on_progress', function($data){
                return '1';
            })
            ->addColumn('complete', function($data){
                return '2';
            })
            ->addColumn('action', function($data){
                return '<a href="'.route('laporan_project_manager.detail', $data->id).'" class="btn btn-warning btn-sm">
                    <span>
                        <i><img src="'.asset('assets/images/eye.svg').'" style="width: 15px;"></i>
                    </span>
                </a>';
            })
            ->rawColumns(['action'])
            ->make(true);                    
        }

        return view('laporan_project_manager.index');
    }
}
