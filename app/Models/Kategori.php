<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $guarded = [];
    protected $primaryKey = 'id'; 

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter->name ?? false, function($query) use ($filter) {
            return $query->where('name', 'like', "%$filter->name%");
        });
    }
}
