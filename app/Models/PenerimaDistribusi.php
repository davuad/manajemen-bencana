<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\PenerimaDistribusiController;


class PenerimaDistribusi extends Model
{
    protected $table = 'penerima_distribusi'; // nama tabel

    protected $primaryKey = 'penerima_id'; // primary key custom

    protected $fillable = [
        'detail_distribusi_id',
        'nama_penerima',
        'jabatan',
        'instansi',
        'alamat',
        'nama_posko',
        'no_hp',
        'status'
    ];

    public function getRouteKeyName()
    {
        return 'penerima_id';
    }

    public function detailDistribusi()
    {
        return $this->belongsTo(
            DetailDistribusi::class,
            'detail_distribusi_id'
        );
    }

}
