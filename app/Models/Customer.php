<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $guarded = [];
    protected $primaryKey = 'id'; 

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
            return $query->where('npwp', 'like', "%$filter->npwp%");
        });
    }
}
