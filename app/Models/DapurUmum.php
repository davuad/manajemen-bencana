<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DapurUmum extends Model
{
    protected $table = 'dapur_umum';

    protected $fillable = [
        'posko_id',
        'nama_dapur_umum',
        'kapasitas_warga',
        'jumlah_warga',
        'penanggung_jawab'
    ];

    public function posko()
    {
        return $this->belongsTo(Posko::class);
    }
}
