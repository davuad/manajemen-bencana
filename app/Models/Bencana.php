<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bencana extends Model
{
    protected $table = 'bencana';

    protected $fillable = [
        'tanggal',
        'jumlah_korban',
        'tingkat_kerusakan'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pengambilan()
    {
        return $this->hasMany(Pengambilan::class, 'bencana_id');
    }
}