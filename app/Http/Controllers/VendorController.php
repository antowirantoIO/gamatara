<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function index()
    {
        $data = Vendor::get();

        return view('vendor.index', Compact('data'));
    }

    public function create()
    {
        return view('vendor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required',
        ]);

        $data = New Vendor();
        $data->name                     = $request->input('name');
        $data->alamat                   = $request->input('alamat');
        $data->contact_person           = $request->input('contact_person');
        $data->nomor_contact_person     = $request->input('nomor_contact_person');
        $data->email                    = $request->input('email');
        $data->npwp                     = $request->input('npwp');
        $data->save();

        return redirect(route('vendor'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function edit(Request $request)
    {
        $data = Vendor::find($request->id);

        return view('vendor.edit', Compact('data'));
    }
    
    public function updated(Request $request)
    {
        $request->validate([
            'name'              => 'required',
        ]);

        $data                           = Vendor::find($request->id);
        $data->name                     = $request->input('name');
        $data->alamat                   = $request->input('alamat');
        $data->contact_person           = $request->input('contact_person');
        $data->nomor_contact_person     = $request->input('nomor_contact_person');
        $data->email                    = $request->input('email');
        $data->npwp                     = $request->input('npwp');
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
}
