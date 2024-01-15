<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewSN extends Model
{
    use HasFactory;

    protected $table = 'snpekerjaanview';

    public function viewAfterPhoto($idProject)
    {
        return $this->hasMany(ViewAfterPhoto::class,'subkategori_concat','subkategori_concat');
    }
    public function viewBeforePhoto($idProject)
    {
        return $this->hasMany(ViewAfterPhoto::class,'subkategori_concat','subkategori_concat');
    }

    public function pekerjaan()
    {
        return $this->belongsTo(ProjectPekerjaan::class,'id_Project','id_project');
    }
}
