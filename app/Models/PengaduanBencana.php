<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaduanBencana extends Model
{
    protected $table = 'pengaduan_bencana';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'kategori_id',
        'desa',
        'deskripsi',
        'status_pengaduan',
        'keterangan_verifikasi',
        'tanggal_selesai'
    ];

    // RELASI
    public function poskos()
    {
        return $this->hasMany(Posko::class, 'pengaduan_bencana_id');
    }
        // relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relasi ke kategori
    public function kategori()
    {
        return $this->belongsTo(KategoriBencana::class, 'kategori_id');
    }

    // relasi ke foto
    public function foto()
    {
        return $this->hasMany(
            FotoPengaduan::class,
            'pengaduan_bencana_id',
            'id'
        );
    }

    // relasi ke kebutuhan
    public function kebutuhan()
    {
        return $this->hasOne(KebutuhanPengaduan::class);
    }
    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}
