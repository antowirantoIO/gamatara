<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\ExportVendor;
use App\Models\Vendor;
use App\Models\KategoriVendor;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Vendor::with(['kategori'])->orderBy('name','asc')
                    ->filter($request);

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('kategori_vendor', function($data){
                return $data->kategori->name ?? '';
            })
            ->addColumn('action', function($data){
                $btnEdit = '';
                $btnDelete = '';
                if($this->authorize('vendor-edit')) {
                    $btnEdit = '<a href="'.route('vendor.edit', $data->id).'" class="btn btn-success btn-sm">
                                    <span>
                                        <i><img src="'.asset('assets/images/edit.svg').'" style="width: 15px;"></i>
                                    </span>
                                </a>';
                }
                if($this->authorize('vendor-delete')){
                    $btnDelete = '<a data-id="'.$data->id.'" data-name="Vendor '.$data->name.'" data-form="form-vendor" class="btn btn-danger btn-sm deleteData">
                                    <span>
                                        <i><img src="'.asset('assets/images/trash.svg').'" style="width: 15px;"></i>
                                    </span>
                                </a>
                                <form method="GET" id="form-vendor'.$data->id.'" action="'.route('vendor.delete', $data->id).'">
                                    '.csrf_field().'
                                    '.method_field('DELETE').'
                                </form>';
                }
                return $btnEdit.'&nbsp;'.$btnDelete;
            })
            ->rawColumns(['action','kategori_vendor'])
            ->make(true);                    
        }

        $kategori_vendor = KategoriVendor::get();

        return view('vendor.index',compact('kategori_vendor'));
    }

    public function create()
    {
        $kategori_vendor = KategoriVendor::get();

        return view('vendor.create',compact('kategori_vendor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required',
            'alamat'                => 'required',
            'contact_person'        => 'required',
            'nomor_contact_person'  => 'required',
            'email'                 => 'required',
            'npwp'                  => 'required|min:15',
            'ttd'                   => 'required',
            'kategori_vendor'       => 'required'
        ]);

        $data = New Vendor();
        $data->name                     = $request->input('name');
        $data->alamat                   = $request->input('alamat');
        $data->contact_person           = $request->input('contact_person');
        $data->nomor_contact_person     = $request->input('nomor_contact_person');
        $data->email                    = $request->input('email');
        $data->npwp                     = $request->input('npwp');
        $data->kategori_vendor          = $request->input('kategori_vendor');
        $data->ttd                      = $request->input('ttd_base64');
        $data->save();

        return redirect(route('vendor'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function edit(Request $request)
    {
        $data = Vendor::find($request->id);
        $kategori_vendor = KategoriVendor::get();

        return view('vendor.edit', Compact('data','kategori_vendor'));
    }
    
    public function updated(Request $request)
    {
        $request->validate([
            'name'                  => 'required',
            'alamat'                => 'required',
            'contact_person'        => 'required',
            'nomor_contact_person'  => 'required',
            'email'                 => 'required',
            'npwp'                  => 'required|min:15',
            'kategori_vendor'       => 'required'
        ]);

        $data                           = Vendor::find($request->id);
        $data->name                     = $request->input('name');
        $data->alamat                   = $request->input('alamat');
        $data->contact_person           = $request->input('contact_person');
        $data->nomor_contact_person     = $request->input('nomor_contact_person');
        $data->email                    = $request->input('email');
        $data->npwp                     = $request->input('npwp');
        $data->kategori_vendor          = $request->input('kategori_vendor');
        $data->ttd                      = $request->input('ttd_base64');
        $data->save();

        return redirect(route('vendor'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function delete($id)
    {
        $data           = Vendor::findOrFail($id);
        $data->delete();

        return redirect(route('vendor'))
                    ->with('success', 'Data berhasil dihapus');
    }

    public function export(Request $request)
    {
        $data = Vendor::filter($request)
                ->get();

        return Excel::download(new ExportVendor($data), 'List Vendor.xlsx');
    }
}
