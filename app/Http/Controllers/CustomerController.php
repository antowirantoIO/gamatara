<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if($request->get('query')){
            $query = $request->get('query');
            $data = Customer::where('name', 'LIKE', '%'. $query. '%')->get();
            return response()->json($data);
        }else{
            $data = Customer::where('status',0)->get();

            return view('customer.index', Compact('data'));
        }
    }
    
    public function create()
    {
        return view('customer.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required',
        ]);

        $data = New Customer();
        $data->name                     = $request->input('name');
        $data->alamat                   = $request->input('alamat');
        $data->contact_person           = $request->input('contact_person');
        $data->nomor_contact_person     = $request->input('nomor_contact_person');
        $data->email                    = $request->input('email');
        $data->npwp                     = $request->input('npwp');
        $data->save();

        return redirect(route('customer'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function edit(Request $request)
    {
        $data = Customer::find($request->id);

        return view('customer.edit', Compact('data'));
    }

    public function updated(Request $request)
    {
        $request->validate([
            'name'              => 'required',
        ]);

        $data                           = Customer::find($request->id);
        $data->name                     = $request->input('name');
        $data->alamat                   = $request->input('alamat');
        $data->contact_person           = $request->input('contact_person');
        $data->nomor_contact_person     = $request->input('nomor_contact_person');
        $data->email                    = $request->input('email');
        $data->npwp                     = $request->input('npwp');
        $data->save();

        return redirect(route('customer'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function delete($id)
    {
        $data           = Customer::findOrFail($id);
        $data->status   = 1;
        $data->save();

        return redirect(route('customer'))
                    ->with('success', 'Data berhasil dihapus');
    }
}
