<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KebutuhanPengaduan extends Model
{
    protected $table = 'kebutuhan_pengaduan';

    protected $fillable = [
        'pengaduan_bencana_id',
        'dapur_umum',
        'psikososial',
        'logistik_rentan',
        'logistik_makanan',
        'logistik_penampungan',
        'keterangan'
    ];

    // relasi ke pengaduan
    public function pengaduan()
    {
        return $this->belongsTo(PengaduanBencana::class, 'pengaduan_bencana_id');
    }
}