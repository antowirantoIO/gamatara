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
        })->when($filter->length ?? false, function($query) use ($filter) {
            return $query->where('length', 'like', "%$filter->length%");
        })->when($filter->width ?? false, function($query) use ($filter) {
            return $query->where('width', 'like', "%$filter->width%");
        })->when($filter->thick ?? false, function($query) use ($filter) {
            return $query->where('thick', 'like', "%$filter->thick%");
        })->when($filter->unit ?? false, function($query) use ($filter) {
            return $query->where('unit', 'like', "%$filter->unit%");
        })->when($filter->conversion ?? false, function($query) use ($filter) {
            return $query->where('conversion', 'like', "%$filter->conversion%");
        });
    }
}
