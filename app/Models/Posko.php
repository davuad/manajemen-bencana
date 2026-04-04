<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posko extends Model
{
    protected $table = 'posko';

    protected $primaryKey = 'id_posko';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_posko',
        'tanggal_dibuat',
        'id_desa',
        'id_pengaduan',
        'lokasi'
    ];

    // RELASI

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'id_desa');
    }

    public function pengaduan()
    {
        return $this->belongsTo(PengaduanBencana::class, 'id_pengaduan');
    }
}
