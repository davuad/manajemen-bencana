<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SumberBarangMasuk extends Model
{
    protected $table = 'sumber_barang_masuk';

    protected $primaryKey = 'id_sumber';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_sumber',
        'nama_sumber',
        'keterangan'
    ];
}