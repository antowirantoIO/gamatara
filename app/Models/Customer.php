<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $guarded = [];
    protected $primaryKey = 'id'; 

    public function projects()
    {
        return $this->HasMany(OnRequest::class, 'id_customer','id');
    }

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter->nama_customer ?? false, function($query) use ($filter) {
            return $query->where('name', 'like', "%$filter->nama_customer%");
        })->when($filter->contact_person ?? false, function($query) use ($filter) {
            return $query->where('contact_person', 'like', "%$filter->contact_person%");
        })->when($filter->nomor_contact_person ?? false, function($query) use ($filter) {
            return $query->where('nomor_contact_person', 'like', "%$filter->nomor_contact_person%");
        })->when($filter->alamat ?? false, function($query) use ($filter) {
            return $query->where('alamat', 'like', "%$filter->alamat%");
        })->when($filter->email ?? false, function($query) use ($filter) {
            return $query->where('email', 'like', "%$filter->email%");
        })->when($filter->npwp ?? false, function($query) use ($filter) {
            return $query->where('tahun', 'like', "%$filter->npwp%");
        })->when($filter->tahun ?? false, function($query) use ($filter) {
            $query->whereHas('projects.progress', function($query) use($filter){
                return $query->whereYear('created_at', '=', $filter->tahun);
            })->orWhereDoesntHave('projects');
        })->when($filter->keyword ?? false, function($query) use ($filter) {
            return $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($filter->keyword) . '%'])
                ->orWhereRaw('LOWER(contact_person) LIKE ?', ['%' . strtolower($filter->keyword) . '%'])
                ->orWhereRaw('LOWER(nomor_contact_person) LIKE ?', ['%' . strtolower($filter->keyword) . '%'])
                ->orWhereRaw('LOWER(alamat) LIKE ?', ['%' . strtolower($filter->keyword) . '%'])
                ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($filter->keyword) . '%'])
                ->orWhereRaw('LOWER(npwp) LIKE ?', ['%' . strtolower($filter->keyword) . '%']);
        });
    }
}
