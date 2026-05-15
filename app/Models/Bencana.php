<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bencana extends Model
{
    protected $table = 'bencana';

    protected $fillable = [
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
    }
}