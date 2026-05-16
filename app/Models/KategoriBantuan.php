<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBantuan extends Model
{
    protected $table = 'kategori_bantuan';

    protected $fillable = [
        'id_sumber',
        'nama_kategori',
        'keterangan'
    ];

    public function sumber()
    {
        return $this->belongsTo(Sumber::class, 'id_sumber');
    }

    public function stok()
    {
        return $this->hasMany(StokGudang::class, 'id_kategori_bantuan');
    }
}