<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingPekerjaan extends Model
{
    protected $table = 'setting_pekerjaan';
    protected $guarded = [];
    protected $primaryKey = 'id'; 

    public function pekerjaan()
    {
        return $this->hasOne(Pekerjaan::class, 'id','id_pekerjaan');
    }

    public function subkategori()
    {
        return $this->hasOne(SubKategori::class, 'id','id_sub_kategori');
    }

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter->subkategori ?? false, function($query) use ($filter) {
            return $query->where('id_sub_kategori', 'like', "%$filter->subkategori%");
        })->when($filter->kategori ?? false, function($query) use ($filter) {
            $query->whereHas('subkategori', function($query) use($filter){
                return $query->where('id_kategori', $filter->kategori);
            });
        })->when($filter->pekerjaan ?? false, function($query) use ($filter) {
            return $query->where('id_pekerjaan', 'like', "%$filter->pekerjaan%");
        })->when($filter->keyword ?? false, function($query) use ($filter) {
            return $query->where(function ($query) use ($filter) {
                $query->where('id_sub_kategori', $filter->kategori);
            })->orWhereHas('subkategori', function($query) use($filter) {
                $query->where('name', 'like', "%$filter->keyword%");
            })->orWhereHas('pekerjaan', function($query) use($filter) {
                $query->where('name', 'like', "%$filter->keyword%");
            });
        });
    }
}
