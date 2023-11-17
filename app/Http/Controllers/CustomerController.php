<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\ExportCustomer;
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
            if ($request->ajax()) {
                $data = Customer::orderBy('name','asc')
                        ->filter($request);
    
                return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($data){
                    $btnEdit = '';
                    $btnDelete = '';
                    if(Can('customer-edit')) {
                        $btnEdit = '<a href="'.route('customer.edit', $data->id).'" class="btn btn-success btn-sm">
                                        <span>
                                            <i><img src="'.asset('assets/images/edit.svg').'" style="width: 15px;"></i>
                                        </span>
                                    </a>';
                    }
                    if(Can('customer-delete')){
                        $btnDelete = '<a data-id="'.$data->id.'" data-name="Customer '.$data->name.'" data-form="form-customer" class="btn btn-danger btn-sm deleteData">
                                        <span>
                                            <i><img src="'.asset('assets/images/trash.svg').'" style="width: 15px;"></i>
                                        </span>
                                    </a>
                                    <form method="GET" id="form-customer'.$data->id.'" action="'.route('customer.delete', $data->id).'">
                                        '.csrf_field().'
                                        '.method_field('DELETE').'
                                    </form>';
                    }
                    return $btnEdit.'&nbsp;'.$btnDelete;
                })
                ->rawColumns(['action'])
                ->make(true);                    
            }
    
            return view('customer.index');
        }
    }
    
    public function create()
    {
        return view('customer.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required',
            'alamat'                => 'required',
            'contact_person'        => 'required',
            'nomor_contact_person'  => 'required',
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
                    ->with('success', 'Data saved successfully');
    }

    public function edit(Request $request)
    {
        $data = Customer::find($request->id);

        return view('customer.edit', Compact('data'));
    }

    public function updated(Request $request)
    {
        $request->validate([
            'name'                  => 'required',
            'alamat'                => 'required',
            'contact_person'        => 'required',
            'nomor_contact_person'  => 'required',
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
                    ->with('success', 'Data saved successfully');
    }

    public function delete($id)
    {
        $data = Customer::findOrFail($id);
        $data->delete();

        return redirect(route('customer'))
                    ->with('success', 'Data successfully deleted');
    }

    public function export(Request $request)
    {
        $data = Customer::orderBy('name','desc')
                ->filter($request)
                ->get();

        return Excel::download(new ExportCustomer($data), 'List Customer.xlsx');
    }
}
