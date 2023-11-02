<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    protected $table = 'pekerjaan';
    protected $guarded = [];
    protected $primaryKey = 'id'; 

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter->name ?? false, function($query) use ($filter) {
            return $query->where('name', 'like', "%$filter->name%");
        })->when($filter->konversi ?? false, function($query) use ($filter) {
            return $query->where('konversi', 'like', "%$filter->konversi%");
        })->when($filter->harga_vendor ?? false, function($query) use ($filter) {
            return $query->where('harga_vendor', 'like', "%$filter->harga_vendor%");
        })->when($filter->harga_customer ?? false, function($query) use ($filter) {
            return $query->where('harga_customer', 'like', "%$filter->harga_customer%");
        })->when($filter->unit ?? false, function($query) use ($filter) {
            return $query->where('unit', 'like', "%$filter->unit%");
        })->when($filter->keyword ?? false, function($query) use ($filter) {
            return $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($filter->keyword) . '%'])
                ->orWhereRaw('LOWER(unit) LIKE ?', ['%' . strtolower($filter->keyword) . '%'])
                ->orWhereRaw('LOWER(konversi) LIKE ?', ['%' . strtolower($filter->keyword) . '%'])
                ->orWhereRaw('LOWER(harga_customer) LIKE ?', ['%' . strtolower($filter->keyword) . '%'])
                ->orWhereRaw('LOWER(harga_vendor) LIKE ?', ['%' . strtolower($filter->keyword) . '%']);
        });
    }
}
