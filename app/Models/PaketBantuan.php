<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketBantuan extends Model
{
    //Yuni
    protected $table = 'paket_bantuan';

    protected $fillable = [
        'posko_id',
        'nama_paket',
        'keterangan',
        'status'
    ];

    public function posko()
    {
        return $this->belongsTo(Posko::class, 'posko_id');
    }

    public function detailPaket()
    {
        return $this->hasMany(DetailPaket::class, 'paket_bantuan_id');
    }

}
