<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bencana;
use App\Models\Pegawai;
use App\Models\DetailPengajuanBarang;
use App\Models\BarangKeluar;
use App\Imports\PengajuanImport;

class PengajuanBarang extends Model
{
    protected $table = 'pengajuan_barang';

    protected $fillable = [
        'bencana_id',
        'pegawai_id',
        'tgl_pengajuan',
        'status_pengajuan',
        'created_by',
        'updated_by',
        'acc_ketua_id',
        'tgl_persetujuan',
        'keterangan',
        'catatan'
    ];

    /**
     * Relasi ke bencana
     */
    public function bencana()
    {
        return $this->belongsTo(Bencana::class, 'bencana_id', 'id');
    }

/**
     * Relasi ke pegawai (pengaju)
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'id_pegawai');
    }

    /**
     * Relasi ke pegawai (ketua yang ACC)
     */
    public function accKetua()
    {
        return $this->belongsTo(Pegawai::class, 'acc_ketua_id', 'id_pegawai');
    }

    /**
     * Relasi ke detail pengajuan
     */
    public function detailPengajuan()
    {
        return $this->hasMany(DetailPengajuanBarang::class);
    }

    /**
     * Relasi ke barang keluar
     */
    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke User pengubah terakhir
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    }

