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
        'pengaduan_bencana_id',
        'tanggal',
        'status_bencana',
        'tingkat_kerusakan',
        'jumlah_korban'
    ];

    // RELASI KE KATEGORI
    public function kategori()
    {
        return $this->belongsTo(KategoriBencana::class, 'kategori_id');
    }

    // RELASI KE DESA
    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    // RELASI KE PENGADUAN
    public function pengaduan()
    {
        return $this->belongsTo(PengaduanBencana::class, 'pengaduan_bencana_id');
    }

    // RELASI KE DISTRIBUSI
    public function distribusis()
    {
        return $this->hasMany(Distribusi::class, 'bencana_id');
    }
}
