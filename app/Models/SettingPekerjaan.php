<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingPekerjaan extends Model
{
    protected $table = 'setting_pekerjaan';
    protected $guarded = [];
    protected $primaryKey = 'id'; 

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter->id_sub_kategori ?? false, function($query) use ($filter) {
            return $query->where('id_sub_kategori', 'like', "%$filter->id_sub_kategori%");
        })->when($filter->id_kategori ?? false, function($query) use ($filter) {
            return $query->where('id_kategori', 'like', "%$filter->id_kategori%");
        })->when($filter->id_pekerjaan ?? false, function($query) use ($filter) {
            return $query->where('id_pekerjaan', 'like', "%$filter->id_pekerjaan%");
        });
    }

    public function pekerjaan()
    {
        return $this->hasOne(Pekerjaan::class, 'id','id_pekerjaan');
    }

    public function subkategori()
    {
        return $this->hasOne(SubKategori::class, 'id','id_sub_kategori');
    }
}
