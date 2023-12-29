<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

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

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class,'id','id_karyawan');
    }

    public function role()
    {
        return $this->belongsTo(Roles::class, 'id_role', 'id');
    }

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter->karyawan ?? false, function($query) use ($filter) {
            $query->whereHas('karyawan', function($query) use($filter){
                return $query->where('name',  'like', "%$filter->karyawan%");
            });
        })->when($filter->nomor_telpon ?? false, function($query) use ($filter) {
            return $query->where('nomor_telpon', 'like', "%$filter->nomor_telpon%");
        })->when($filter->email ?? false, function($query) use ($filter) {
            return $query->where('email', 'like', "%$filter->email%");
        })->when($filter->role ?? false, function($query) use ($filter) {
            return $query->where('id_role', $filter->role);
        })->when($filter->keyword ?? false, function($query) use ($filter) {
            return $query->where(function ($query) use ($filter) {
                $query->where('nomor_telpon', 'like', "%$filter->keyword%")
                    ->orWhere('email', 'like', "%$filter->keyword%");
            })->orWhereHas('role', function($query) use($filter) {
                $query->where('name', 'like', "%$filter->keyword%");
            })->orWhereHas('karyawan', function($query) use($filter) {
                $query->where('name', 'like', "%$filter->keyword%");
            });
        });
    }
}
