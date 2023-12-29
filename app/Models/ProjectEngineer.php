<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectEngineer extends Model
{
    protected $table = 'pe';
    protected $guarded = [];
    protected $primaryKey = 'id'; 

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class, 'id','id_karyawan');
    }
    
    public function pm()
    {
        return $this->belongsTo(ProjectManager::class, 'id_pm');
    }
}
