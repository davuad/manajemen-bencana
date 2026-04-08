<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaduanBencana extends Model
{
    protected $table = 'pengaduan_bencana';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_kategori',
        'id_user',
        'desa',
        'deskripsi',
        'status_pengaduan',
        'keterangan_verifikasi',
        'tanggal_selesai'
    ];

    // RELASI
    public function poskos()
    {
        return $this->hasMany(Posko::class, 'id_pengaduan');
    }
}
