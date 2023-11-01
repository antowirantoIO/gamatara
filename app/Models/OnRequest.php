<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnRequest extends Model
{
    protected $table = 'project';
    protected $guarded = [];
    protected $primaryKey = 'id';

    public function kapal()
    {
        return $this->hasOne(JenisKapal::class, 'id','id_jenis_kapal');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class,'id','id_customer');
    }

    public function keluhan()
    {
        return $this->hasMany(Keluhan::class,'on_request_id','id');
    }

    public function pm()
    {
        return $this->hasOne(ProjectManager::class,'id','pm_id');
    }

    public function complaint()
    {
        return $this->hasMany(Keluhan::class,'on_request_id','id');
    }

    public function lokasi()
    {
        return $this->hasOne(LokasiProject::class,'id','id_lokasi_project');
    }

    public function progress()
    {
        return $this->hasMany(ProjectPekerjaan::class,'id_project','id');
    }

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter->code ?? false, function($query) use ($filter) {
            return $query->where('code', 'like', "%$filter->code%");
        })->when($filter->nama_project ?? false, function($query) use ($filter) {
            return $query->where('nama_project', 'like', "%$filter->nama_project%");
        })->when($filter->nama_customer ?? false, function($query) use ($filter) {
            return $query->where('id_customer', 'like', "%$filter->nama_customer%");
        })->when(($filter->start_date ?? false) || ($filter->to_date ?? false), function ($query) use ($filter) {
            $query->when($filter->start_date ?? false, function ($query) use ($filter) {
                return $query->whereDate('created_at', '>=', $filter->start_date);
            })->when($filter->to_date ?? false, function ($query) use ($filter) {
                return $query->whereDate('created_at', '<=', $filter->to_date);
            });
        })->when($filter->displacement ?? false, function($query) use ($filter) {
            return $query->where('displacement', 'like', "%$filter->displacement%");
        })->when($filter->jenis_kapal ?? false, function($query) use ($filter) {
            return $query->where('id_jenis_kapal', 'like', "%$filter->jenis_kapal%");
        })->when($filter->keyword ?? false, function($query) use ($filter) {
            return $query->where(function ($query) use ($filter) {
                $query->where('code', 'like', "%$filter->keyword%")
                    ->orWhere('nama_project', 'like', "%$filter->keyword%")
                    ->orWhere('created_at', 'like', "%$filter->keyword%")
                    ->orWhere('displacement', 'like', "%$filter->keyword%");
            })->orWhereHas('kapal', function($query) use($filter) {
                $query->where('name', 'like', "%$filter->keyword%");
            })->orWhereHas('customer', function($query) use($filter) {
                $query->where('name', 'like', "%$filter->keyword%");
            });
        });
    }
}
