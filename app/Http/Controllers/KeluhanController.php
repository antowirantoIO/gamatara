<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keluhan;
use App\Models\OnRequest;
use App\Models\ProjectAdmin;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Auth;
use PDF;

class KeluhanController extends Controller
{
    public function store(Request $request)
    {        
        if($request->keluhanId == null)
        {
            $code = 'SPK'.'/'.'GTS'.'/'.now()->format('Y')."/".now()->format('m').'/';
            $projectCode = Keluhan::where('no_spk', 'LIKE', '%'.$code.'%')->count();
            $randInt = '001';
            if ($projectCode >= 1) {
                $count = $projectCode+1;
                $randInt = str_pad($count, 3, '0', STR_PAD_LEFT);
            }
            $randInt = substr($randInt, -5);

            $keluhan                = new Keluhan();
            $keluhan->on_request_id = $request->id;
            $keluhan->id_vendor     = $request->vendor;
            $keluhan->keluhan       = str_replace('\n', '<br/>', $request->input('keluhan'));
            $keluhan->no_spk        = $code.$randInt;
            $keluhan->save();

            return response()->json([
                    'message' => 'Request successfully added',
                    'status' => 200, 
                    'id' => $keluhan->id, 
                    'id_vendor' => $keluhan->id_vendor
                ]);
        }else{
            $keluhan                = Keluhan::find($request->keluhanId);
            $keluhan->on_request_id = $request->id;
            $keluhan->id_vendor     = $request->vendor;
            $keluhan->keluhan       = str_replace('\n', '<br/>', $request->input('keluhan'));
            $keluhan->save();

            return response()->json([
                'keluhan' => $keluhan->keluhan,
                'message' => 'Request successfully modified',
                'status' => 200
            ]);
        }       
    }

    public function getData(Request $request)
    {        
        $data   = Keluhan::find($request->id);

        return response()->json(['status' => 200,'data' => $data]);
    }

    public function approve(Request $request)
    {        
        $data   = Keluhan::find($request->id);     

        if($request->type == 'PM')
        {
            $data->id_pm_approval   = Auth::user()->id;
            $data->pm_date_approval = Carbon::now();
        }
        else{
            $data->id_bod_approval      = Auth::user()->id;
            $data->bod_date_approval    = Carbon::now();
        }
        $data->save();

        return response()->json(['status' => 200, 'message' => 'Successfully Approved']);
    }

    public function delete(Request $request)
    {
        try {
            $keluhan = Keluhan::findOrFail($request->id);
            $keluhan->delete();
     
            return response()->json(['message' => 'Request Deleted Successfully','status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete request'], 500);
        }
    }

    public function SPK(Request $request)
    {
        $data       = OnRequest::find($request->id);
        $keluhan    = Keluhan::join('vendor', 'project_request.id_vendor', '=', 'vendor.id')
                        ->where('project_request.on_request_id', $request->id)
                        ->orderBy('vendor.name', 'asc')
                        ->get();

        $data['created']        = Carbon::parse($data->created_at)->format('d M Y');
        $data['target_selesai'] = Carbon::parse($data->target_selesai)->format('d M Y');

        foreach($keluhan as $value)
        {
           if($value){
                if($value->bod_date_approval != null){
                    $value['bod_approval'] = Carbon::parse($value->bod_date_approval)->format('d M Y');
                }else{
                    $value['bod_approval'] = "-";
                }
           }
        }

        $cetak      = "Rekap SPK.pdf";
        $pdf = PDF::loadview('pdf.spk', compact('data','keluhan'))
                    ->setPaper('A4', 'potrait')
                    ->setOptions(['isPhpEnabled' => true, 'enable_remote' => true]);
        return $pdf->stream($cetak);
    }

    public function SPKSatuan(Request $request)
    {
        $keluhan = Keluhan::find($request->id);
        $data = OnRequest::find($keluhan->on_request_id); 
        $data['created_ats'] =  Carbon::parse($data->created_at)->format('d M Y');
        $cetak = $data->nama_project."_".$keluhan->vendors->name."_".$keluhan->no_spk.".pdf";
        $pm = User::find($keluhan->id_pm_approval);
        $bod = User::find($keluhan->id_bod_approval);
        $pa = ProjectAdmin::with('karyawan.user')->find($data->pa_id);
        $vendor = Vendor::find($keluhan->id_vendor);
        $total = count(OnRequest::get());
        $total = str_pad($total, 3, '0', STR_PAD_LEFT);

        $data['approvalPA'] = $pa->karyawan->name ?? '';
        $data['ttdPA'] = $pa->karyawan->user ? $pa->karyawan->user->ttd : '';
        $data['approvalPM'] = $pm->karyawan->name ?? '';
        $data['ttdPM'] = $pm->ttd ?? '';
        $data['approvalBOD'] = $bod->karyawan->name ?? '';
        $data['ttdBOD'] = $bod->ttd ?? '';
        $data['ttdVendor'] = $vendor->ttd ?? '';
        $data['po_no'] = $keluhan->no_spk ?? '';

        if($data->pm)
        {
            $cek = $data->pm; 
            foreach($cek->pes as $value)
            {
                $value['pe_name'] =  $value->karyawan->name ?? '';
            }

            foreach($cek->pas as $value){
                $value['pa_name'] =  $value->karyawan->name ?? '';
            }
        } 

        $pdf = PDF::loadview('pdf.spksatuan', compact('data','keluhan'))
                    ->setPaper('A4', 'landscape')
                    ->setOptions(['isPhpEnabled' => true, 'enable_remote' => true]);
        return $pdf->stream($cetak);
    }

}
