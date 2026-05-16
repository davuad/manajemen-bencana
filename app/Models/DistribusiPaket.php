<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistribusiPaket extends Model
{
    protected $table = 'distribusi_paket';

    protected $fillable = [
        'warga_terdampak_id',
        'paket_bantuan_id',
        'jumlah_paket',
        'tanggal_distribusi',
        'pegawai_id',
        'status_distribusi',
    ];

    protected $casts = [
        'tanggal_distribusi' => 'date',
    ];

    public function wargaTerdampak()
    {
        return $this->belongsTo(WargaTerdampak::class, 'warga_terdampak_id');
    }

    public function paketBantuan()
    {
        return $this->belongsTo(PaketBantuan::class, 'paket_bantuan_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'id_pegawai');
    }
}
