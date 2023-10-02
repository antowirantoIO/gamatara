<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKapal extends Model
{
    protected $table = 'jenis_kapal';
    protected $guarded = [];
    protected $primaryKey = 'id'; 
}
