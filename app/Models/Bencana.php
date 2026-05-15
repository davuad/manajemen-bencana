<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bencana extends Model
{
    protected $table = 'bencana';

    protected $fillable = [
<<<<<<< HEAD
        'nama_bencana',
        'kategori_id',
        'desa_id',
        'pengaduan_id',
        'tanggal',
        'status_bencana',
        'tingkat_kerusakan'
    ];

    // 🔗 Relasi ke kategori
    public function kategori()
    {
        return $this->belongsTo(KategoriBencana::class, 'kategori_id');
    }

        public function desa()
    {
        return $this->belongsTo(\App\Models\Desa::class, 'desa_id');
    }

    public function pengaduan()
    {
        return $this->belongsTo(\App\Models\PengaduanBencana::class, 'pengaduan_id');
=======
        'kategori_id',
        'pengaduan_bencana_id',
        'desa_id',
        'tanggal',
        'jumlah_korban',
        'tingkat_kerusakan'
    ];

    // RELASI
    public function pengaduan()
    {
        return $this->belongsTo(PengaduanBencana::class, 'pengaduan_bencana_id');
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function distribusis()
    {
        return $this->hasMany(Distribusi::class, 'bencana_id');
>>>>>>> 3241a2ad534f6283bc7cc1abd0b47eebdac8db76
    }
}