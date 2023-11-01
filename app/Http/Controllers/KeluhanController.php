<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keluhan;
use App\Models\OnRequest;
use App\Models\User;
use App\Models\Vendor;
use Auth;
use PDF;

class KeluhanController extends Controller
{
    public function store(Request $request)
    {        
        if($request->keluhanId == null)
        {

            $cekReq = count(Keluhan::where('on_request_id',$request->id)->where('id_vendor',$request->vendor)->get());
            if($cekReq > 0){
                return response()->json(
                    [
                        'message' => 'Vendor Sudah Ada',
                        'status' => 500
                    ]
                );
            }else{
                $keluhan                = new Keluhan();
                $keluhan->on_request_id = $request->id;
                $keluhan->id_vendor     = $request->vendor;
                $keluhan->keluhan       = str_replace('\n', '<br/>', $request->input('keluhan'));
                $keluhan->save();

                return response()->json(
                    [
                        'message' => 'Keluhan berhasil ditambahkan',
                        'status' => 200, 
                        'id' => $keluhan->id, 
                        'id_vendor' => $keluhan->id_vendor
                    ]
                );
            }
        }else{
            $keluhan                = Keluhan::find($request->keluhanId);
            $keluhan->on_request_id = $request->id;
            $keluhan->id_vendor     = $request->vendor;
            $keluhan->keluhan       = str_replace('\n', '<br/>', $request->input('keluhan'));
            $keluhan->save();

            return response()->json(['keluhan' => $keluhan->keluhan,'message' => 'Keluhan berhasil diubah','status' => 200,]);
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
        }
        else{
            $data->id_bod_approval  = Auth::user()->id;
        }
        $data->save();

        return response()->json(['status' => 200, 'message' => 'Berhasil Di Approve']);
    }

    public function delete(Request $request)
    {
        try {
            $keluhan = Keluhan::findOrFail($request->id);
            $keluhan->delete();
     
            return response()->json(['message' => 'Keluhan berhasil dihapus','status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus keluhan'], 500);
        }
    }

    public function SPK(Request $request)
    {
        $data = OnRequest::find($request->id);
        $keluhan = Keluhan::where('on_request_id',$request->id)->get();
        $cetak = "Rekap SPK ('.date('d F Y').').pdf";

        $pdf = PDF::loadview('pdf.spk', compact('data','keluhan'))
                    ->setPaper('A4', 'portrait')
                    ->setOptions(['isPhpEnabled' => true, 'enable_remote' => true]);
        return $pdf->stream($cetak);
    }

    public function SPKSatuan(Request $request)
    {
        $keluhan = Keluhan::find($request->id);
        $data = OnRequest::find($keluhan->on_request_id); 
        $cetak = "SPK ('.date('d F Y').').pdf";
        $pm = User::find($keluhan->id_pm_approval);
        $bod = User::find($keluhan->id_bod_approval);
        $pa = User::find($keluhan->user_id);
        $vendor = Vendor::find($keluhan->id_vendor);
        $total = count(OnRequest::get());
        $total = str_pad($total, 3, '0', STR_PAD_LEFT);

        $data['approvalPA'] = $pa->karyawan->name ?? '';
        $data['ttdPA'] = $pa->ttd ?? '';
        $data['approvalPM'] = $pm->karyawan->name ?? '';
        $data['ttdPM'] = $pm->ttd ?? '';
        $data['approvalBOD'] = $bod->karyawan->name ?? '';
        $data['ttdBOD'] = $bod->ttd ?? '';
        $data['ttdVendor'] = $vendor->ttd ?? '';
        $data['po_no'] = 'PO'.'/'.'GTS'.'/'.now()->format('Y')."/".now()->format('m').'/'.$total;

        if($data->pm)
        {
            $cek = $data->pm; 
            foreach($cek->pe as $value)
            {
                $value['pe_name'] =  $value->karyawan->name ?? '';
            }

            foreach($cek->pa as $value){
                $value['pa_name'] =  $value->karyawan->name ?? '';
            }
        } 

        $pdf = PDF::loadview('pdf.spksatuan', compact('data','keluhan'))
                    ->setPaper('A4', 'portrait')
                    ->setOptions(['isPhpEnabled' => true, 'enable_remote' => true]);
        return $pdf->stream($cetak);
    }

}
