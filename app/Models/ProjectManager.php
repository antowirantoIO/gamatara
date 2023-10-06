<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectManager extends Model
{
    protected $table = 'pm';
    protected $guarded = [];
    protected $primaryKey = 'id'; 
    
    public function karyawan()
    {
        return $this->hasOne(Karyawan::class, 'id','id_karyawan');
    }

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter->name ?? false, function($query) use ($filter) {
            return $query->where('id_karyawan',$filter->name);
        });
    }
}
