<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPekerjaan extends Model
{
    use HasFactory;

    protected $table = 'project_pekerjaan';
    protected $guarded = [];

    public function vendors()
    {
        return $this->belongsTo(Vendor::class,'id_vendor','id');
    }

    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class,'id_pekerjaan','id');
    }

    public function projects()
    {
        return $this->belongsTo(OnRequest::class,'id_project','id');
    }
}
