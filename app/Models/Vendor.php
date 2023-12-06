<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendor';
    protected $guarded = [];
    protected $primaryKey = 'id'; 

    public function kategori()
    {
        return $this->hasOne(kategoriVendor::class, 'id','kategori_vendor');
    }

    public function projectPekerjaan()
    {
        return $this->hasMany(ProjectPekerjaan::class, 'id_vendor','id');
    }

    public function requests()
    {
        return $this->belongsTo(Keluhan::class, 'id_vendor','id');
    }

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter->name ?? false, function($query) use ($filter) {
            return $query->where('name', 'like', "%$filter->name%");
        })->when($filter->contact_person ?? false, function($query) use ($filter) {
            return $query->where('contact_person', 'like', "%$filter->contact_person%");
        })->when($filter->nomor_contact_person ?? false, function($query) use ($filter) {
            return $query->where('nomor_contact_person', 'like', "%$filter->nomor_contact_person%");
        })->when($filter->alamat ?? false, function($query) use ($filter) {
            return $query->where('alamat', 'like', "%$filter->alamat%");
        })->when($filter->email ?? false, function($query) use ($filter) {
            return $query->where('email', 'like', "%$filter->email%");
        })->when($filter->npwp ?? false, function($query) use ($filter) {
            return $query->where('npwp', 'like', "%$filter->npwp%");
        })->when($filter->kategori_vendor ?? false, function($query) use ($filter) {
            return $query->where('kategori_vendor', 'like', "%$filter->kategori_vendor%");
        })->when($filter->tahun ?? false, function ($query) use ($filter) {
            return $query->whereHas('projectPekerjaan', function ($query) use ($filter) {
                $query->whereYear('created_at', '=', $filter->tahun);
            })->orWhereDoesntHave('projectPekerjaan');
        })->when($filter->keyword ?? false, function($query) use ($filter) {
        return $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($filter->keyword) . '%'])
                ->orWhereRaw('LOWER(contact_person) LIKE ?', ['%' . strtolower($filter->keyword) . '%'])
                ->orWhereRaw('LOWER(nomor_contact_person) LIKE ?', ['%' . strtolower($filter->keyword) . '%'])
                ->orWhereRaw('LOWER(alamat) LIKE ?', ['%' . strtolower($filter->keyword) . '%'])
                ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($filter->keyword) . '%'])
                ->orWhereRaw('LOWER(npwp) LIKE ?', ['%' . strtolower($filter->keyword) . '%']);
            })->orWhereHas('kategori', function($query) use($filter) {
                $query->where('name', 'like', "%$filter->keyword%");
        });
      
    }
}
