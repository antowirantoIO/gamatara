<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Exports\ExportKaryawan;
use App\Models\Karyawan;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Karyawan::where('status',0)->orderBy('name','asc')
                    ->filter($request);

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function($data){
                $btnEdit = '';
                $btnDelete = '';
                if(Can('karyawan-edit')) {
                    $btnEdit = '<a href="'.route('karyawan.edit', $data->id).'" class="btn btn-success btn-sm">
                                    <span>
                                        <i><img src="'.asset('assets/images/edit.svg').'" style="width: 15px;"></i>
                                    </span>
                                </a>';
                }
                if(Can('karyawan-delete')){
                    $btnDelete = '<a data-id="'.$data->id.'" data-name="Employee '.$data->name.'" data-form="form-karyawan" class="btn btn-danger btn-sm deleteData">
                                    <span>
                                        <i><img src="'.asset('assets/images/trash.svg').'" style="width: 15px;"></i>
                                    </span>
                                </a>
                                <form method="GET" id="form-karyawan'.$data->id.'" action="'.route('karyawan.delete', $data->id).'">
                                    '.csrf_field().'
                                    '.method_field('DELETE').'
                                </form>';
                }
                return $btnEdit.'&nbsp;'.$btnDelete;
            })
            ->rawColumns(['action'])
            ->make(true);                    
        }

        return view('karyawan.index');
    }

    public function create()
    {
        $role   = Role::orderBy('name','asc')->get();

        return view('karyawan.create',compact('role'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required',
            'jabatan'               => 'required',
            'alamat'                => 'required',
            'nomor_telpon'          => 'required',
            'email'                 => 'required'
        ]);

        $data = New Karyawan();
        $data->name                     = $request->input('name');
        $data->jabatan                  = $request->input('jabatan');
        $data->alamat                   = $request->input('alamat');
        $data->nomor_telpon             = $request->input('nomor_telpon');
        $data->email                    = $request->input('email');
        $data->save();

        return redirect(route('karyawan'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function edit(Request $request)
    {
        $data   = Karyawan::find($request->id);
        $role   = Role::orderBy('name','asc')->get();

        return view('karyawan.edit', Compact('data','role'));
    }

    public function updated(Request $request)
    {
        $request->validate([
            'name'                  => 'required',
            'jabatan'               => 'required',
            'alamat'                => 'required',
            'nomor_telpon'          => 'required',
            'email'                 => 'required'
        ]);

        $data                           = Karyawan::find($request->id);
        $data->name                     = $request->input('name');
        $data->jabatan                  = $request->input('jabatan');
        $data->alamat                   = $request->input('alamat');
        $data->nomor_telpon             = $request->input('nomor_telpon');
        $data->email                    = $request->input('email');
        $data->save();

        return redirect(route('karyawan'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function delete($id)
    {
        $data           = Karyawan::findOrFail($id);
        $data->status   = 1;
        $data->save();

        return redirect(route('karyawan'))
                    ->with('success', 'Data berhasil dihapus');
    }

    public function export(Request $request)
    {
        $data = Karyawan::orderBy('name','desc')
                ->filter($request)
                ->get();

        return Excel::download(new ExportKaryawan($data), 'List Karyawan.xlsx');
    }
}
