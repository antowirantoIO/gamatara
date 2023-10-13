<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Roles::class, 'id_role', 'id');
    }

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class,'id','id_karyawan');
    }

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter->karyawan ?? false, function($query) use ($filter) {
            return $query->where('id_karyawan', 'like', "%$filter->karyawan%");
        })->when($filter->role ?? false, function($query) use ($filter) {
            return $query->where('id_role', 'like', "%$filter->role%");
        })->when($filter->nomor_telpon ?? false, function($query) use ($filter) {
            return $query->where('nomor_telpon', 'like', "%$filter->nomor_telpon%");
        })->when($filter->email ?? false, function($query) use ($filter) {
            return $query->where('email', 'like', "%$filter->email%");
        });
    }
}
