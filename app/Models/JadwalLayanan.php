<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalLayanan extends Model
{
    //Anisa
    protected $table = 'jadwal';

    protected $fillable = [
        'bencana_id',
        'pegawai_id',
        'tanggal_layanan',
        'jam_mulai',
        'jam_selesai',
        'jenis_layanan',
        'sarana',
        'petugas_lapangan',
        'lokasi_layanan',
        'status'
    ];

    public function bencana()
    {
        return $this->belongsTo(Bencana::class);
    }

    public function pegawai()
    {
       return $this->belongsTo(Pegawai::class, 'pegawai_id', 'id_pegawai');
    }
}
