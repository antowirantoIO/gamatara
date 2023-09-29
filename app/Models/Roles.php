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
}
