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

    public function pe()
    {
        return $this->hasMany(ProjectEngineer::class, 'id_pm');
    }

    public function pa()
    {
        return $this->hasMany(ProjectAdmin::class, 'id_pm');
    }

    public function projects()
    {
        return $this->HasMany(OnRequest::class, 'pm_id','id');
    }

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter->pm ?? false, function($query) use ($filter) {
            return $query->where('id_karyawan',$filter->pm);
        })->when($filter->name ?? false, function($query) use($filter) {
            // $query->whereHas()
        });
    }
}
