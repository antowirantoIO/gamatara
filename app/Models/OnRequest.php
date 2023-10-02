<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnRequest extends Model
{
    protected $table = 'on_request';
    protected $guarded = [];
    protected $primaryKey = 'id'; 
}
