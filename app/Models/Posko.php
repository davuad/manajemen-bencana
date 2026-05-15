<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posko extends Model
{
    protected $table = 'posko';

    protected $fillable = [
        'nama_posko',
        'tanggal_dibuat',
        'desa_id',
        'bencana_id',
        'pengaduan_bencana_id',
        'lokasi',
        'status'
    ];

    // RELASI

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function pengaduan()
    {
        return $this->belongsTo(PengaduanBencana::class, 'pengaduan_bencana_id');
    }

    public function bencana()
    {
        return $this->belongsTo(Bencana::class);
    }
}
