<?php

namespace App\Http\Controllers;

use App\Models\ProjectPlanner;
use Illuminate\Http\Request;
use App\Models\OnRequest;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\Keluhan;
use App\Models\ProjectPekerjaan;
use App\Models\ProjectManager;
use App\Models\ProjectAdmin;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $cekRole = Auth::user()->role->name;
        $cekId = Auth::user()->id_karyawan;
        $user = Auth::user();
        $userRole = $user->role->name ?? '';
        $cekPm = ProjectAdmin::where('id_karyawan',$cekId)->first();
        $cekPa  = ProjectManager::where('id_karyawan', $cekId)->first();
        $result = ProjectManager::get()->toArray();

        $karyawanId = $user->karyawan->id ?? null;

        $spkrequest = OnRequest::with(['kapal', 'customer']);

        switch ($userRole) {
            case 'Project Manager':
            case 'PM':
                $pm = ProjectManager::where('id_karyawan', $karyawanId)->first();
                $spkrequest = $pm ? $spkrequest->where('pm_id', $pm->id) : $spkrequest->where('id', 0);
                break;

            case 'Project Admin':
            case 'PA':
                $pa = ProjectAdmin::where('id_karyawan', $karyawanId)->first();
                $spkrequest = $pa ? $spkrequest->where('pa_id', $pa->id) : $spkrequest->where('id', 0);
                break;

            case 'SPV Project Planner':
                $pp = ProjectPlanner::where('id_karyawan', $karyawanId)->first();
                $spkrequest = $pp ? $spkrequest->where('pp_id', $pp->id) : $spkrequest->where('id', 0);
                break;

            case 'BOD':
            case 'BOD1':
            case 'Super Admin':
            case 'Administrator':
            case 'Staff Finance':
            case 'SPV Finance':
                $pmIds = ProjectManager::pluck('id')->toArray();
                $spkrequest = !empty($pmIds) ? $spkrequest->whereIn('pm_id', $pmIds) : $spkrequest->where('id', 0);
                break;

            default:
                $spkrequest->where('id', 0); // No access
                break;
        }

        $spkrequest = $spkrequest->whereHas('complaint', function ($query) use ($cekRole) {
            if ($cekRole == 'Project Manager') {
                $query->whereNull('id_pm_approval')->whereNull('id_bod_approval');
            } elseif ($cekRole == 'BOD') {
                $query->whereNotNull('id_pm_approval')->whereNull('id_bod_approval');
            }
        })->get();

        $pekerjaan = OnRequest::whereNull('approval_bod')->whereNotNull(['approval_pm'])->get();
        $keluhan = $spkrequest->map(function ($item) use ($cekRole) {
            $jumlahKeluhan = $item->complaint->filter(function ($complaint) use ($cekRole) {
                if ($cekRole == 'Project Manager') {
                    return is_null($complaint->id_pm_approval) && is_null($complaint->id_bod_approval);
                } elseif ($cekRole == 'BOD') {
                    return !is_null($complaint->id_pm_approval) && is_null($complaint->id_bod_approval);
                }
                return false;
            })->count();

            return [
                'id' => $item->id,
                'code' => $item->code,
                'nama_project' => $item->nama_project,
                'jumlah' => $jumlahKeluhan,
            ];
        })->toArray();

        $spkrequest = count($spkrequest);

        $totalcustomer = count(Customer::get());
        $totalvendor = count(Vendor::get());

        $progress = ProjectPekerjaan::whereNotNull('id_pekerjaan')
                    ->select('id_vendor')
                    ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as onprogress')
                    ->selectRaw('SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as complete')
                    ->groupBy('id_vendor')
                    ->orderByDesc('complete')
                    ->get();

        $pm = ProjectManager::with(['projects' => function ($query) {
                $query->select('pm_id')
                    ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as onprogress')
                    ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as complete')
                    ->groupBy('pm_id');
            }])
            ->selectRaw('pm.*, (SELECT SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) FROM project WHERE project.pm_id = pm.id) as complete')
            ->orderByDesc('complete')
            ->get();

        $data = OnRequest::with(['kapal', 'customer']);

        switch ($userRole) {
            case 'Project Manager':
            case 'PM':
                $pmModel = ProjectManager::where('id_karyawan', $karyawanId)->first();
                $data = $pmModel ? $data->where('pm_id', $pmModel->id) : $data->where('id', 0);
                break;

            case 'Project Admin':
            case 'PA':
                $pa = ProjectAdmin::where('id_karyawan', $karyawanId)->first();
                $data = $pa ? $data->where('pa_id', $pa->id) : $data->where('id', 0);
                break;

            case 'SPV Project Planner':
                $pp = ProjectPlanner::where('id_karyawan', $karyawanId)->first();
                $data = $pp ? $data->where('pp_id', $pp->id) : $data->where('id', 0);
                break;

            case 'BOD':
            case 'BOD1':
            case 'Super Admin':
            case 'Administrator':
            case 'Staff Finance':
            case 'SPV Finance':
                $pmIds = ProjectManager::pluck('id')->toArray();
                $data = !empty($pmIds) ? $data->whereIn('pm_id', $pmIds) : $data->where('id', 0);
                break;

            default:
                $data->where('id', 0); // No access
                break;
        }

        $datas = $data->where('status',1)
        ->orderBy('created_at', 'desc')
        ->get();

        $onprogress =   $data->whereHas('keluhan',function($query){
                $query->whereNotNull(['id_pm_approval','id_bod_approval']);
            })
            ->where('status',1)
            ->orderBy('created_at','desc')
            ->get();

        $onprogress = count($onprogress);

        $datap = OnRequest::with(['kapal', 'customer']);

        switch ($userRole) {
            case 'Project Manager':
            case 'PM':
                $pmModel = ProjectManager::where('id_karyawan', $karyawanId)->first();
                $datap = $pmModel ? $datap->where('pm_id', $pmModel->id) : $datap->where('id', 0);
                break;

            case 'Project Admin':
            case 'PA':
                $pa = ProjectAdmin::where('id_karyawan', $karyawanId)->first();
                $datap = $pa ? $datap->where('pa_id', $pa->id) : $datap->where('id', 0);
                break;

            case 'SPV Project Planner':
                $pp = ProjectPlanner::where('id_karyawan', $karyawanId)->first();
                $datap = $pp ? $datap->where('pp_id', $pp->id) : $datap->where('id', 0);
                break;

            case 'BOD':
            case 'BOD1':
            case 'Super Admin':
            case 'Administrator':
            case 'Staff Finance':
            case 'SPV Finance':
                $pmIds = ProjectManager::pluck('id')->toArray();
                $datap = !empty($pmIds) ? $datap->whereIn('pm_id', $pmIds) : $datap->where('id', 0);
                break;

            default:
                $datap->where('id', 0); // No access
                break;
        }

        $complete = $datap->where('status',2)->get();
        $complete = count($complete);

        return view('dashboard',compact('keluhan','spkrequest','onprogress','complete','totalcustomer','totalvendor','datas','progress','pm','pekerjaan'));
    }
}
