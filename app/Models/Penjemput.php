<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjemput extends Model
{
     protected $table = 'penjemput';

    protected $fillable = [
        'nama_penjemput',
        'nik',
        'hubungan_dengan_anak',
        'alamat',
        'no_hp',
    ];
}
