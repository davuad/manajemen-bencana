<?php
// app/Imports/PengajuanImport.php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PengajuanImport implements ToModel, WithHeadingRow
{
    public array $importData = [];

public function model(array $row)
{
    $rawTanggal = $row['tanggal_bencana'] ?? ($row['tanggal'] ?? null);
    $fixedTanggal = now()->format('Y-m-d'); 

    if ($rawTanggal) {
        if (is_numeric($rawTanggal)) {
            $fixedTanggal = \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rawTanggal))->format('Y-m-d');
        } else {
            try {
                $fixedTanggal = \Carbon\Carbon::parse(str_replace('/', '-', $rawTanggal))->format('Y-m-d');
            } catch (\Exception $e) {
                $fixedTanggal = now()->format('Y-m-d');
            }
        }
    }

    $desaNama = $row['nama_desa'] ?? ($row['desa'] ?? null);
    $namaBarang = $row['nama_barang'] ?? ($row['namabarang'] ?? ($row['barang'] ?? null));
    $namaPegawai = $row['nama_pegawai'] ?? ($row['namapegawai'] ?? ($row['pegawai'] ?? null));

    $this->importData[] = [
        'desa_nama'         => $desaNama ? trim($desaNama) : null,
        'kecamatan'         => $row['kecamatan'] ?? 'Cilacap',
        'kategori_nama'     => $row['kategori_bencana'] ?? null,
        'tanggal'           => $fixedTanggal, 
        'jumlah_korban'     => $row['jumlah_korban'] ?? 0,
        'nama_pegawai'      => $namaPegawai ? trim($namaPegawai) : null, // 🟢 Bersihkan spasi
        'barang_nama_excel' => $namaBarang ? trim($namaBarang) : null,   // 🟢 Bersihkan spasi
        'jumlah'            => $row['jumlah'] ?? 0,
        'kategori_penerima' => $row['kategori_penerima'] ?? 'warga',
    ];

    return null;
}
}