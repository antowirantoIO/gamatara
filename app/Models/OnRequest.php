<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnRequest extends Model
{
    protected $table = 'project';
    protected $guarded = [];
<<<<<<< HEAD
    protected $primaryKey = 'id';

    public function customer ()
    {
        return $this->hasOne(Customer::class,'id','id_customer');
    }

    public function complaint()
    {
        return $this->hasMany(Keluhan::class,'on_request_id','id');
=======
    protected $primaryKey = 'id'; 
    
    public function kapal()
    {
        return $this->hasOne(JenisKapal::class, 'id','id_jenis_kapal');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id','id_customer');
>>>>>>> a7497cea6f651df9f692da50c24c837768bd5915
    }
}
