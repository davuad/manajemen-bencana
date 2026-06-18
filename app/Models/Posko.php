<?php

namespace App\Models;

use App\Models\DapurUmum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Posko extends Model
{
    protected $table = 'posko';

    protected $fillable = [
        'nama_posko',
        'tanggal_dibuat',
        'desa_id',
        'bencana_id',
        'pengaduan_bencana_id',
        'lokasi',
        'status'
    ];

    // RELASI

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function pengaduan()
    {
        return $this->belongsTo(PengaduanBencana::class, 'pengaduan_bencana_id');
    }

    public function stokPosko()
    {
        return $this->hasMany(StokPosko::class, 'posko_id');
    }
    
    public function bencana()
    {
        return $this->belongsTo(Bencana::class);
    }

    public function korban()
    {
        return $this->hasMany(Korban::class);
    }

    public function dapurUmum(): HasMany
    {
        return $this->hasMany(DapurUmum::class, 'posko_id');
    }
}
