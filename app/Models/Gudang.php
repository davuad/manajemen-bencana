<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    protected $table = 'gudang';

    protected $fillable = [
        'nama_gudang',
        'alamat',
        'kapasitas',
        'keterangan'
    ];

    public function stok()
    {
        return $this->hasMany(StokGudang::class, 'id_gudang');
    }
}