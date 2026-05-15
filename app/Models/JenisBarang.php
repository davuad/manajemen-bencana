<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisBarang extends Model
{
    protected $table = 'jenis_barang';

    protected $primaryKey = 'id_jenis_barang';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_jenis_barang',
        'nama_jenis_barang',
        'keterangan'
    ];

    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_jenis_barang');
    }
}