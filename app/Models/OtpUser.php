<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpUser extends Model
{
    use HasFactory;
    protected $table = 'otp_user';
    protected $guarded = [];
    public $timestamps =false;
}
