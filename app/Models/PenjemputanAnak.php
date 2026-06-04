<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjemputanAnak extends Model
{
    protected $table = 'penjemputan_anak';

    protected $fillable = [
        'anak_id',
        'penjemput_id',
        'petugas_id',
        'tanggal_penjemputan',
        'status_verifikasi',
        'catatan',
        'bukti_dokumen',
        'berita_acara',
    ];

    public function anak()
    {
        return $this->belongsTo(AnakTerpisah::class, 'anak_id');
    }

    public function penjemput()
    {
        return $this->belongsTo(Penjemput::class, 'penjemput_id');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'petugas_id');
    }
}