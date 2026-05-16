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

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relasi ke kategori_bencana
    public function kategori()
    {
        return $this->belongsTo(KategoriBencana::class, 'kategori_id', 'id');
    }

    // Relasi ke desa
    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id', 'id');
    }

    // Relasi ke pengaduan_bencana
    public function pengaduan()
    {
        return $this->belongsTo(PengaduanBencana::class, 'pengaduan_id', 'id');
    }

    // Relasi ke distribusi (kalau memang tabel distribusi masih pakai bencana_id)
    public function distribusis()
    {
        return $this->hasMany(Distribusi::class, 'bencana_id', 'id');
    }
}
