<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawan';
    protected $guarded = [];
    protected $primaryKey = 'id'; 

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
        });
    }
}
