<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnRequest extends Model
{
    protected $table = 'project';
    protected $guarded = [];
    protected $primaryKey = 'id'; 
    
    public function kapal()
    {
        return $this->hasOne(JenisKapal::class, 'id','id_jenis_kapal');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id','id_customer');
    }
}
