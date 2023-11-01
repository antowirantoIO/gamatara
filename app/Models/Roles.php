<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles';
    protected $guarded = [];
    protected $primaryKey = 'id'; 

    public function users()
    {
        return $this->hasOne(Roles::class, 'jabatan','id');
    }

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter->name ?? false, function($query) use ($filter) {
            return $query->where('name', 'like', "%$filter->name%");
        })->when($filter->keyword ?? false, function($query) use ($filter) {
            return $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($filter->keyword) . '%']);
        });
    }
}
