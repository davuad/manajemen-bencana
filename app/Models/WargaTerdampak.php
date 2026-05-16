<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WargaTerdampak extends Model
{
    protected $table = 'warga_terdampak';

    protected $fillable = [
        'no_kk',
        'nik_kepala_keluarga',
        'nama_kepala_keluarga',
        'alamat',
        'desa_id',
        'bencana_id',
        'jumlah_anggota',
        'tanggal_pendataan',
        'jenis_bantuan',
        'status_penyaluran',
        'tanggal_penyaluran',
    ];

    protected $casts = [
        'tanggal_pendataan' => 'date',
        'tanggal_penyaluran' => 'date',
    ];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class, 'desa_id', 'id');
    }

    public function bencana(): BelongsTo
    {
        return $this->belongsTo(Bencana::class, 'bencana_id', 'id');
    }
}
