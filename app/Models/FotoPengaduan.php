<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoPengaduan extends Model
{
    protected $table = 'foto_pengaduan';

    protected $fillable = [
        'pengaduan_bencana_id',
        'file_foto',
        'keterangan'
    ];

    // relasi ke pengaduan
    public function pengaduan()
    {
        return $this->belongsTo(PengaduanBencana::class, 'pengaduan_bencana_id');
    }
    
}