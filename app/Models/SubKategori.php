<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKategori extends Model
{
    protected $table = 'sub_kategori';
    protected $guarded = [];
    protected $primaryKey = 'id'; 

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter->name ?? false, function($query) use ($filter) {
            return $query->where('name', 'like', "%$filter->name%");
        })->when($filter->kategori ?? false, function($query) use ($filter) {
            return $query->where('id_kategori', 'like', "%$filter->kategori%");
        });
    }

    public function kategori()
    {
        return $this->hasOne(Kategori::class, 'id','id_kategori');
    }
}
