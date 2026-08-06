<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Bencana;
use App\Models\DetailBarangKeluar;
use App\Models\DetailDistribusi;
use App\Models\Distribusi;
use App\Models\Posko;
use App\Models\StokPosko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class DistribusiController extends Controller
{
    // ================= INDEX =================
    public function index(Request $request)
    {
        $query = Distribusi::with([
            'bencana',
            'posko.desa',
            'detailDistribusis.detailBarangKeluar.barang'
        ]);

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('kategori_distribusi', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");

                $q->orWhereHas('bencana', function ($bencana) use ($search) {
                    $bencana->where('nama_bencana', 'like', "%{$search}%");
                });

                $q->orWhereHas('posko', function ($posko) use ($search) {
                    $posko->where('nama_posko', 'like', "%{$search}%");
                });

                $q->orWhereHas('posko.desa', function ($desa) use ($search) {
                    $desa->where('nama_desa', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_distribusi', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_distribusi', $request->tahun);
        }

        $tahunSekarang = now()->year;
        $tahunList = collect([$tahunSekarang, $tahunSekarang - 1, $tahunSekarang - 2]);

        $distribusi = $query->latest()->get();

        $totalData = $distribusi->count();

        return view(
            'management_distribusi.distribusi.index',
            compact('distribusi', 'totalData', 'tahunList')
        );
    }

    // ================= CREATE =================
    public function create()
    {
        $barangKeluar = DetailBarangKeluar::with([
            'barang',
            'barangKeluar'
        ])
            ->orderBy('id', 'asc')
            ->get();

        return view('management_distribusi.distribusi.create', [
            'barangKeluar' => $barangKeluar,
            'bencana'      => Bencana::all(),
            'posko'        => Posko::with('desa')->get(),
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
                'barang_detail.*.detail_barang_keluar_id' => 'required',
                'barang_detail.*.jumlah_kirim' => 'required|integer|min:1',
                'barang_detail.*.satuan' => 'required',
            ]);

            // =====================
            // SIMPAN DISTRIBUSI
            // =====================
            $distribusi = Distribusi::create([
                'bencana_id'          => $request->bencana_id,
                'posko_id'            => $request->posko_id,
                'tanggal_distribusi'  => $request->tanggal_distribusi,
                'lokasi_distribusi'   => $request->lokasi_distribusi,
                'kendaraan'           => $request->kendaraan,
                'nama_supir'          => $request->nama_supir,
                'nomor_kendaraan'     => $request->nomor_kendaraan,
                'kategori_distribusi' => $request->kategori_distribusi,
                'status'              => $request->status,
                'keterangan'          => $request->keterangan,
            ]);

            // =====================
            // SIMPAN DETAIL DISTRIBUSI
            // =====================
            foreach ($request->barang_detail as $detail) {

                $detailBarangKeluar = DetailBarangKeluar::find(
                    $detail['detail_barang_keluar_id']
                );

                if (!$detailBarangKeluar) {
                    continue;
                }

                // Validasi jumlah kirim
                if ($detail['jumlah_kirim'] > $detailBarangKeluar->jumlah_keluar) {
                    throw new \Exception(
                        'Jumlah kirim melebihi jumlah barang keluar.'
                    );
                }

                DetailDistribusi::create([
                    'distribusi_id'            => $distribusi->id,
                    'detail_barang_keluar_id'  => $detailBarangKeluar->id,
                    'jumlah_kirim'             => $detail['jumlah_kirim'],
                    'satuan'                   => $detail['satuan'],
                ]);
            }

            DB::commit();

            $prefix = auth()->user()->hasRole('admin') ? 'admin' : 'pegawai';
            return redirect()
                ->route($prefix . '.management_distribusi.distribusi.index')
                ->with('success', 'Data distribusi berhasil ditambahkan.');
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
            'bencana',
            'posko.desa',
            'detailDistribusis.detailBarangKeluar.barang'
        ])->findOrFail($id);

        $barangKeluar = DetailBarangKeluar::with([
            'barang',
            'barangKeluar'
        ])->get();

        return view('management_distribusi.distribusi.edit', [
            'distribusi'   => $distribusi,
            'barangKeluar' => $barangKeluar,
            'bencana'      => Bencana::all(),
            'posko'        => Posko::with('desa')->get(),
        ]);
    }


    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $distribusi = Distribusi::findOrFail($id);

            //Fitur Stok Posko Otomatis (Yuni)//
            $statusLama = $distribusi->status;

            

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
                'barang_detail.*.detail_barang_keluar_id' => 'required',
                'barang_detail.*.jumlah_kirim' => 'required|integer|min:1',
                'barang_detail.*.satuan' => 'required',
            ]);

            // ==========================
            // UPDATE DATA DISTRIBUSI
            // ==========================
            $distribusi->update([
                'bencana_id'          => $request->bencana_id,
                'posko_id'            => $request->posko_id,
                'tanggal_distribusi'  => $request->tanggal_distribusi,
                'lokasi_distribusi'   => $request->lokasi_distribusi,
                'kendaraan'           => $request->kendaraan,
                'nama_supir'          => $request->nama_supir,
                'nomor_kendaraan'     => $request->nomor_kendaraan,
                'kategori_distribusi' => $request->kategori_distribusi,
                'status'              => $request->status,
                'keterangan'          => $request->keterangan,
            ]);

            //Fitur Stok Posko Otomatis (Yuni)//
            if (
                $statusLama !== 'selesai'
                && $request->status === 'selesai'
            ) {

                foreach ($request->barang_detail as $detail) {

                    $detailBarangKeluar = DetailBarangKeluar::find(
                        $detail['detail_barang_keluar_id']
                    );

                    if (!$detailBarangKeluar) {
                        continue;
                    }

                    $stok = StokPosko::where(
                        'posko_id',
                        $request->posko_id
                    )
                        ->where(
                            'barang_id',
                            $detailBarangKeluar->barang_id
                        )
                        ->where(
                            'kategori_distribusi',
                            $request->kategori_distribusi
                        )
                        ->first();

                    if ($stok) {

                        $stok->update([
                            'jumlah_barang' =>
                            $stok->jumlah_barang +
                                $detail['jumlah_kirim']
                        ]);
                    } else {

                        StokPosko::create([
                            'posko_id' => $request->posko_id,
                            'barang_id' => $detailBarangKeluar->barang_id,
                            'kategori_distribusi' =>
                            $request->kategori_distribusi,
                            'jumlah_barang' =>
                            $detail['jumlah_kirim']
                        ]);
                    }
                }
            }

            // ==========================
            // HAPUS DETAIL LAMA
            // ==========================
            $distribusi->detailDistribusis()->delete();

            // ==========================
            // SIMPAN DETAIL BARU
            // ==========================
            foreach ($request->barang_detail as $detail) {

                if (empty($detail['detail_barang_keluar_id'])) {
                    continue;
                }

                $detailBarangKeluar = DetailBarangKeluar::find(
                    $detail['detail_barang_keluar_id']
                );

                if (!$detailBarangKeluar) {
                    continue;
                }

                if ($detail['jumlah_kirim'] > $detailBarangKeluar->jumlah_keluar) {
                    throw new \Exception(
                        'Jumlah kirim tidak boleh melebihi jumlah keluar.'
                    );
                }

                DetailDistribusi::create([
                    'distribusi_id'            => $distribusi->id,
                    'detail_barang_keluar_id'  => $detailBarangKeluar->id,
                    'jumlah_kirim'             => $detail['jumlah_kirim'],
                    'satuan'                   => $detail['satuan'],
                ]);
            }

            DB::commit();

            $prefix = auth()->user()->hasRole('admin') ? 'admin' : 'pegawai';
            return redirect()
                ->route($prefix . '.management_distribusi.distribusi.index')
                ->with('success', 'Data distribusi berhasil diperbarui.');
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
            'detailDistribusis.detailBarangKeluar.barang',
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
