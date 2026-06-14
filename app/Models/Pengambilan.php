<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengambilan extends Model
{
    protected $table = 'pengambilan';

    protected $fillable = [
        'barang_id',
        'bencana_id',
        'petugas_id',
        'posko_id',
        'tanggal_pengambilan',
        'jumlah_ambil',
        'tujuan',
        'status'
    ];

    /**
     * Relasi ke tabel barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /**
     * Relasi ke tabel bencana
     */
    public function bencana()
    {
        return $this->belongsTo(Bencana::class, 'bencana_id');
    }

    /**
     * Relasi ke tabel petugas
     */
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'petugas_id');
    }

    /**
     * Relasi ke tabel posko
     */
    public function posko()
    {
        return $this->belongsTo(Posko::class, 'posko_id');
    }
}