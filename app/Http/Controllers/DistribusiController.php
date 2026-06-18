<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Bencana;
use App\Models\DetailBarangKeluar;
use App\Models\DetailDistribusi;
use App\Models\Distribusi;
use App\Models\Posko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistribusiController extends Controller
{
    // ================= INDEX =================
    public function index(Request $request)
{
    $query = Distribusi::with([
        'bencana',
        'posko.desa'
    ]);

    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            // tabel distribusi
            $q->where('kategori_distribusi', 'like', "%{$search}%")
              ->orWhere('status', 'like', "%{$search}%");

            // tabel bencana
            $q->orWhereHas('bencana', function ($bencana) use ($search) {
                $bencana->where(
                    'nama_bencana',
                    'like',
                    "%{$search}%"
                );
            });

            // tabel posko
            $q->orWhereHas('posko', function ($posko) use ($search) {
                $posko->where(
                    'nama_posko',
                    'like',
                    "%{$search}%"
                );
            });

            // tabel desa
            $q->orWhereHas('posko.desa', function ($desa) use ($search) {
                $desa->where(
                    'nama_desa',
                    'like',
                    "%{$search}%"
                );
            });
        });
    }

    $distribusi = $query->latest()->get();

    return view(
        'management_distribusi.distribusi.index',
        compact('distribusi')
    );
}

    // ================= CREATE =================
    public function create()
    {
        return view('management_distribusi.distribusi.create', [
            'barangKeluar' => DetailBarangKeluar::with([
                'barang',
                'barangKeluar'
            ])->get(),

            'bencana' => Bencana::all(),
            'posko' => Posko::with('desa')->get(),
        ]);
    }

    // ================= STORE =================
 public function store(Request $request)
{
    DB::beginTransaction();

    try {

        $request->validate([
            'bencana_id' => 'required',
            'posko_id' => 'required',
            'tanggal_distribusi' => 'required|date',
            'lokasi_distribusi' => 'required',
            'kendaraan' => 'required',
            'nama_supir' => 'required',
            'nomor_kendaraan' => 'required',
            'kategori_distribusi' => 'required',
            'status' => 'required',

            'barang_detail' => 'required|array',
            'barang_detail.*.barang_keluar_id' => 'required',
            'barang_detail.*.jumlah_kirim' => 'required|integer|min:1',
            'barang_detail.*.satuan' => 'required',
        ]);

        // SIMPAN DISTRIBUSI
        $distribusi = Distribusi::create([
            'bencana_id' => $request->bencana_id,
            'posko_id' => $request->posko_id,
            'tanggal_distribusi' => $request->tanggal_distribusi,
            'lokasi_distribusi' => $request->lokasi_distribusi,
            'kendaraan' => $request->kendaraan,
            'nama_supir' => $request->nama_supir,
            'nomor_kendaraan' => $request->nomor_kendaraan,
            'keterangan' => $request->keterangan,
            'kategori_distribusi' => $request->kategori_distribusi,
            'status' => $request->status,
        ]);

        // SIMPAN DETAIL DISTRIBUSI
        foreach ($request->barang_detail as $detail) {

            if (empty($detail['barang_keluar_id'])) {
                continue;
            }

            $detailBarangKeluar = DetailBarangKeluar::find(
                $detail['barang_keluar_id']
            );

            if (!$detailBarangKeluar) {
                continue;
            }

            // VALIDASI JUMLAH KIRIM
            if ($detail['jumlah_kirim'] > $detailBarangKeluar->jumlah_keluar) {
                throw new \Exception(
                    'Jumlah kirim melebihi jumlah barang keluar'
                );
            }

            DetailDistribusi::create([
                'distribusi_id' => $distribusi->id,

                // FK ke tabel barang_keluar
                'barang_keluar_id' => $detailBarangKeluar->barang_keluar_id,

                'jumlah_kirim' => $detail['jumlah_kirim'],
                'satuan' => $detail['satuan'],
            ]);
        }

        DB::commit();

        return redirect()
            ->route('admin.management_distribusi.distribusi.index')
            ->with('success', 'Data distribusi berhasil ditambahkan');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->withErrors($e->getMessage());
    }
}
    // ================= EDIT =================
public function edit($id)
{
    $distribusi = Distribusi::with([
        'detailDistribusis.barangKeluar',
        'bencana',
        'posko.desa'
    ])->findOrFail($id);

    return view('management_distribusi.distribusi.edit', [
        'distribusi' => $distribusi,

        'barangKeluar' => DetailBarangKeluar::with([
            'barang',
            'barangKeluar'
        ])->get(),

        'bencana' => Bencana::all(),
        'posko' => Posko::with('desa')->get(),
    ]);
}

    // ================= UPDATE =================
 public function update(Request $request, $id)
{
    DB::beginTransaction();

    try {

        $distribusi = Distribusi::findOrFail($id);

        $request->validate([
            'bencana_id' => 'required',
            'posko_id' => 'required',
            'tanggal_distribusi' => 'required|date',
            'lokasi_distribusi' => 'required',
            'kendaraan' => 'required',
            'nama_supir' => 'required',
            'nomor_kendaraan' => 'required',
            'kategori_distribusi' => 'required',
            'status' => 'required',

            'barang_detail' => 'required|array',
            'barang_detail.*.barang_keluar_id' => 'required',
            'barang_detail.*.jumlah_kirim' => 'required|integer|min:1',
            'barang_detail.*.satuan' => 'required',
        ]);

        // UPDATE DATA DISTRIBUSI
        $distribusi->update([
            'bencana_id' => $request->bencana_id,
            'posko_id' => $request->posko_id,
            'tanggal_distribusi' => $request->tanggal_distribusi,
            'lokasi_distribusi' => $request->lokasi_distribusi,
            'kendaraan' => $request->kendaraan,
            'nama_supir' => $request->nama_supir,
            'nomor_kendaraan' => $request->nomor_kendaraan,
            'keterangan' => $request->keterangan,
            'kategori_distribusi' => $request->kategori_distribusi,
            'status' => $request->status,
        ]);

        // HAPUS DETAIL LAMA
        $distribusi->detailDistribusis()->delete();

        // SIMPAN ULANG DETAIL
        foreach ($request->barang_detail as $detail) {

            if (empty($detail['barang_keluar_id'])) {
                continue;
            }

            $detailBarangKeluar = DetailBarangKeluar::find(
                $detail['barang_keluar_id']
            );

            if (!$detailBarangKeluar) {
                continue;
            }

            if ($detail['jumlah_kirim'] > $detailBarangKeluar->jumlah_keluar) {
                throw new \Exception(
                    'Jumlah kirim melebihi jumlah barang keluar'
                );
            }

            DetailDistribusi::create([
                'distribusi_id' => $distribusi->id,

                // WAJIB pakai barang_keluar_id asli
                'barang_keluar_id' => $detailBarangKeluar->barang_keluar_id,

                'jumlah_kirim' => $detail['jumlah_kirim'],
                'satuan' => $detail['satuan'],
            ]);
        }

        DB::commit();

        return redirect()
            ->route('admin.management_distribusi.distribusi.index')
            ->with('success', 'Data distribusi berhasil diupdate');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->withErrors($e->getMessage());
    }
}

public function show($id)
{
    $distribusi = Distribusi::with([
        'bencana',
        'posko.desa',
        'detailDistribusis.barangKeluar.detailBarangKeluar.barang'
    ])->findOrFail($id);

    return view(
        'management_distribusi.distribusi.show',
        compact('distribusi')
    );
}

    // ================= DELETE =================
    public function destroy($id)
    {
        $distribusi = Distribusi::findOrFail($id);

        $distribusi->detailDistribusis()->delete();
        $distribusi->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }
}