<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\ExportSettingPekerjaan;
use App\Models\SubKategori;
use App\Models\Kategori;
use App\Models\SettingPekerjaan;
use App\Models\Pekerjaan;

class SettingPekerjaanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SettingPekerjaan::with(['subkategori','pekerjaan'])
                    ->filter($request);

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('kategori', function($data){
                return $data->subkategori->kategori->name ?? '';
            })
            ->addColumn('subkategori', function($data){
                return $data->subkategori->name ?? '';
            })
            ->addColumn('pekerjaan', function($data){
                return $data->pekerjaan->name ?? '';
            })
            ->addColumn('action', function($data){
                return '<a href="'.route('setting_pekerjaan.edit', $data->id).'" class="btn btn-success btn-sm">
                    <span>
                        <i><img src="'.asset('assets/images/edit.svg').'" style="width: 15px;"></i>
                    </span>
                </a>
                &nbsp;
                <a data-id="'.$data->id.'" data-name="setting_pekerjaan '.$data->name.'" data-form="form-setting_pekerjaan" class="btn btn-danger btn-sm deleteData">
                    <span>
                        <i><img src="'.asset('assets/images/trash.svg').'" style="width: 15px;"></i>
                    </span>
                </a>
                <form method="GET" id="form-setting_pekerjaan'.$data->id.'" action="'.route('setting_pekerjaan.delete', $data->id).'">
                    '.csrf_field().'
                    '.method_field('DELETE').'
                </form>';
            })
            ->rawColumns(['action'])
            ->make(true);                    
        }

        $kategori = Kategori::get();
        $subkategori = SubKategori::get();
        $pekerjaan  = Pekerjaan::get();

        return view('setting_pekerjaan.index',compact('kategori','subkategori','pekerjaan'));
    }

    public function getKategori(Request $request)
    {
        $data = SettingPekerjaan::with(['subkategori', 'subkategori.kategori'])
        ->where('id_sub_kategori', $request->sub_kategori);
    
        if ($request->pekerjaan != null) {
            $data->where('id_pekerjaan', $request->pekerjaan);
        }
        
        $data = $data->get();
    
        foreach($data as $d)
        {
            $d['kategori'] = $d->subkategori->kategori->name;
            $d['sub_kategori'] = $d->subkategori->name;
            $d['pekerjaan'] = $d->pekerjaan->name;
        }
 
        return response()->json(['status' => 200,'data' => $data]);
    }

    public function create()
    {
        $kategori = Kategori::get();
        $subkategori = SubKategori::get();
        $pekerjaan  = Pekerjaan::get();

        return view('setting_pekerjaan.create',compact('kategori','subkategori','pekerjaan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pekerjaan'     => 'required',
            'subkategori'   => 'required'
        ]);

        $data                   = New SettingPekerjaan();
        $data->id_pekerjaan     = $request->input('pekerjaan');
        $data->id_sub_kategori  = $request->input('subkategori');
        $data->save();

        return redirect(route('setting_pekerjaan.create'))
                    ->with('success', 'Data saved successfully');
    }

    public function edit(Request $request)
    {
        $data = SettingPekerjaan::with(['subkategori'])->first();
        $kategori = Kategori::get();
        $subkategori = SubKategori::where('id',$data->id_sub_kategori)->get();
        $pekerjaan  = Pekerjaan::get();

        return view('setting_pekerjaan.edit', Compact('data','subkategori','pekerjaan','kategori'));
    }

    public function updated(Request $request)
    {
        $request->validate([
            'pekerjaan'     => 'required',
            'subkategori'   => 'required'
        ]);

        $data                   = SettingPekerjaan::find($request->id);
        $data->id_pekerjaan     = $request->input('pekerjaan');
        $data->id_sub_kategori  = $request->input('subkategori');
        $data->save();

        return redirect(route('setting_pekerjaan'))
                    ->with('success', 'Data saved successfully');
    }

    public function delete($id)
    {
        $data           = SettingPekerjaan::findOrFail($id);
        $data->delete();

        return redirect(route('setting_pekerjaan'))
                    ->with('success', 'Data successfully deleted');
    }
    
    public function export(Request $request)
    {
        $data = SettingPekerjaan::orderBy('id','desc')
                ->filter($request)
                ->get();

        return Excel::download(new ExportSettingPekerjaan($data), 'List Setting Pekerjaan.xlsx');
    }
}
