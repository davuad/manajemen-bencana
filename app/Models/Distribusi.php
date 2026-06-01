<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribusi extends Model
{
    use HasFactory;

    protected $table = 'distribusi';

    protected $fillable = [
        'tanggal_distribusi',
        'lokasi_distribusi',
        'kendaraan',
        'nama_supir',
        'nomor_kendaraan',
        'keterangan',
        'kategori_distribusi',
        'status',
    ];
}