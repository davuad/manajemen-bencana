<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Petugas extends Model
{
    use SoftDeletes;

    protected $table = 'petugas';

    protected $fillable = [
        'nama_petugas',
        'jabatan',
        'no_hp',
        'tahun',
        'status',
        'posko_id'
    ];

    public function posko()
    {
        return $this->belongsTo(Posko::class, 'posko_id');
    }

    public function pengambilan()
    {
        return $this->hasMany(Pengambilan::class, 'petugas_id');
    }
}