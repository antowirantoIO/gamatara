<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keluhan extends Model
{
    protected $table = 'project_request';
    protected $guarded = [];
    protected $primaryKey = 'id';

    public function vendors()
    {
        return $this->belongsTo(Vendor::class,'id_vendor','id');
    }

    public function projects()
    {
        return $this->belongsTo(OnRequest::class,'on_request_id','id');
    }
    
}
