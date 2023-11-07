<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentActivity extends Model
{
    use HasFactory;
    protected $table = 'recent_activity';
    protected $guarded = [];
    public $timestmaps = false;


    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class,'id_pekerjaan','id');
    }
}
