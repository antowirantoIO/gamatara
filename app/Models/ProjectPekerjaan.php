<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPekerjaan extends Model
{
    use HasFactory;

    protected $table = 'project_pekerjaan';
    protected $guarded = [];

    public function vendors()
    {
        return $this->belongsTo(Vendor::class,'id_vendor','id');
    }

    public function beforePhoto()
    {
        return $this->hasMany(BeforePhoto::class,'kode_unik','kode_unik');
    }

    public function lokasi ()
    {
        return $this->belongsTo(LokasiProject::class,'id_lokasi','id');
    }

    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class,'id_pekerjaan','id');
    }

    public function projects()
    {
        return $this->belongsTo(OnRequest::class,'id_project','id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class,'id_kategori','id');
    }

    public function subKategori()
    {
        return $this->belongsTo(SubKategori::class,'id_subkategori','id');
    }

    public function activity()
    {
        return $this->hasMany(RecentActivity::class,'project_pekerjaan_id','id')
                    ->orderBy('created_at', 'desc')
                    ->skip(1)
                    ->take(1)
                    ->first();
    }

    public function activitys()
    {
        return $this->hasMany(RecentActivity::class,'project_pekerjaan_id','id')
                    ->orderBy('created_at', 'desc');
    }

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter->code ?? false, function($query) use ($filter) {
            return $query->WhereHas('projects', function($query) use($filter) {
                $query->where('code', 'like', "%$filter->code%");
            });
        })->when($filter->nama_project ?? false, function($query) use ($filter) {
            return $query->WhereHas('projects', function($query) use($filter) {
                $query->where('nama_project', 'like', "%$filter->nama_project%");
            });
        })->when($filter->status_project ?? false, function($query) use ($filter) {
            return $query->WhereHas('projects', function($query) use($filter) {
                $query->where('status', $filter->status_project);
            });
        })->when($filter->dates ?? false, function($query) use ($filter) {
            list($start_date, $end_date) = explode(' - ', $filter->input('dates'));
                $query->whereBetween('created_at', [$start_date, $end_date]);
        })->when($filter->enddates ?? false, function($query) use ($filter) {
            list($start_date, $end_date) = explode(' - ', $filter->input('enddates'));
                $query->whereBetween('created_at', [$start_date, $end_date]);
        })->when(($filter->start_date ?? false) || ($filter->to_date ?? false), function ($query) use ($filter) {
            return $query->WhereHas('projects', function($query) use($filter) {
                $query->when($filter->start_date ?? false, function ($query) use ($filter) {
                    return $query->whereDate('start_project', '>=', $filter->start_date);
                })->when($filter->to_date ?? false, function ($query) use ($filter) {
                    return $query->whereDate('start_project', '<=', $filter->to_date);
                });
            });
        })->when(($filter->start_date_selesai ?? false) || ($filter->to_date_selesai ?? false), function ($query) use ($filter) {
            return $query->WhereHas('projects', function($query) use($filter) {
                $query->when($filter->start_date_selesai ?? false, function ($query) use ($filter) {
                    return $query->whereDate('actual_selesai', '>=', $filter->start_date_selesai);
                })->when($filter->to_date_selesai ?? false, function ($query) use ($filter) {
                    return $query->whereDate('actual_selesai', '<=', $filter->to_date_selesai);
                });
            });
        })->when($filter->nilai_project ?? false, function($query) use ($filter) {
            return $query->where('harga_customer', $filter->harga_customer);
        })->when($filter->keyword ?? false, function($query) use ($filter) {
            return $query->where(function ($query) use ($filter) {
                $query->where('harga_customer', 'like', "%$filter->keyword%");
                $query->where('deskripsi_subkategori', 'like', "%$filter->keyword%");
            })->orWhereHas('projects', function($query) use($filter) {
                $query->where('nama_project', 'like', "%$filter->keyword%")
                ->orWhere('code', 'like', "%$filter->keyword%")
                ->orWhere('start_project', 'like', "%$filter->keyword%")
                ->orWhere('actual_selesai', 'like', "%$filter->keyword%");
            })->orWhereHas('subKategori', function($query) use($filter) {
                $query->where('name', 'like', "%$filter->keyword%");
            })->orWhereHas('vendors', function($query) use($filter) {
                $query->where('name', 'like', "%$filter->keyword%");
            });
        })->when($filter->vendor ?? false, function($query) use ($filter) {
            return $query->whereIn('id_vendor', json_decode($filter->vendor));
        });
    }
}
