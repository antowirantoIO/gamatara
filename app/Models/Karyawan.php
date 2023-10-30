<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawan';
    protected $guarded = [];
    protected $primaryKey = 'id'; 

    public function pm()
    {
        return $this->belongsTo(ProjectManager::class, 'id','id_karyawan');
    }

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter->name ?? false, function($query) use ($filter) {
            return $query->where('name', 'like', "%$filter->name%");
        })->when($filter->alamat ?? false, function($query) use ($filter) {
            return $query->where('alamat', 'like', "%$filter->alamat%");
        })->when($filter->nomor_telpon ?? false, function($query) use ($filter) {
            return $query->where('nomor_telpon', 'like', "%$filter->nomor_telpon%");
        })->when($filter->email ?? false, function($query) use ($filter) {
            return $query->where('email', 'like', "%$filter->email%");
        })->when($filter->jabatan ?? false, function($query) use ($filter) {
            return $query->where('jabatan', 'like', "%$filter->jabatan%");
        })->when($filter->keyword ?? false, function($query) use ($filter) {
            return $query->where(function ($query) use ($filter) {
                $query->where('nomor_telpon', 'like', "%$filter->keyword%")
                    ->orWhere('email', 'like', "%$filter->keyword%")
                    ->orWhere('alamat', 'like', "%$filter->keyword%")
                    ->orWhere('name', 'like', "%$filter->keyword%")
                    ->orWhere('jabatan', 'like', "%$filter->keyword%");
            });
        });
    }
}
