<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relawan extends Model
{
    protected $table = 'relawan';
    protected $primaryKey = 'id_relawan';

    protected $fillable = [
        'nama_relawan',
        'jenis_psks',
        'kecamatan',
        'no_hp',
        'alamat'
    ];
}
