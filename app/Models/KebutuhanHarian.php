<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KebutuhanHarian extends Model
{
    protected $table = 'kebutuhan_harian';

    protected $fillable = [
        'tanggal',
        'jumlah_warga',
        'porsi_per_orang',
        'total_porsi',
        'dapur_umum_id',
    ];

    /**
     * Relasi ke tabel dapur
     */
    public function dapur_umum()
    {
        return $this->belongsTo(DapurUmum::class);
    }
}
