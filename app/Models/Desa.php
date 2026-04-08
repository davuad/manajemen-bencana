<?php

namespace App\Models;

use App\Models\Posko;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    protected $table = 'desa';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_desa',
        'kecamatan',
        'nama_kades',
        'kontak_kades'
    ];

    // RELASI
    public function poskos()
    {
        return $this->hasMany(Posko::class, 'id_desa');
    }
}
