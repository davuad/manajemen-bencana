<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnakTerpisah extends Model
{
    protected $table = 'anak_terpisah';

    protected $fillable = [
        'nama_anak',
        'jenis_kelamin',
        'umur',
        'tanggal_lahir',
        'alamat_asal',
        'lokasi_ditemukan',
        'tanggal_ditemukan',
        'nama_ortu_wali',
        'kontak_keluarga',
        'status_anak',
        'foto_anak',
        'bencana_id',
    ];

    public function penjemputan()
    {
        return $this->hasOne(PenjemputanAnak::class, 'anak_id');
    }
}