<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAdmin extends Model
{
    protected $table = 'pa';
    protected $guarded = [];
    protected $primaryKey = 'id'; 

    public function pm()
    {
        return $this->belongsTo(ProjectManager::class, 'id_pm');
    }
}
