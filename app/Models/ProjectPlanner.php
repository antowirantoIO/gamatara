<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPlanner extends Model
{
    protected $table = 'pp';
    protected $guarded = [];
    protected $primaryKey = 'id'; 

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class, 'id','id_karyawan');
    }
}
