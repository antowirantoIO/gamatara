<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeforePhoto extends Model
{
    protected $table = 'before_photo';
    protected $guarded = [];
    protected $primaryKey = 'id'; 
}
