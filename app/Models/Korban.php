<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Korban extends Model
{
    protected $table = 'korban';

    protected $fillable = [
        'bencana_id',
        'posko_id',
        'user_id',
        'nama',
        'nik',
        'jenis_kelamin',
        'umur',
        'alamat',
        'lokasi_kejadian',
        'tanggal_kejadian',
    ];

    public function bencana()
    {
        return $this->belongsTo(Bencana::class);
    }

    public function posko()
    {
        return $this->belongsTo(Posko::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}