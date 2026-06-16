<?php

namespace App\Http\Controllers;

use App\Models\Distribusi;
use App\Models\BarangKeluar;
use App\Models\Bencana;
use App\Models\Posko;
use App\Models\DetailDistribusi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistribusiController extends Controller
{
    // ================= INDEX =================
    public function index(Request $request)
    {
        $query = Distribusi::with([
            'detailDistribusis.barangKeluar.barang',
            'bencana',
            'posko.desa'
        ]);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('lokasi_distribusi', 'like', "%{$request->search}%")
                    ->orWhere('kendaraan', 'like', "%{$request->search}%")
                    ->orWhere('nama_supir', 'like', "%{$request->search}%")
                    ->orWhere('nomor_kendaraan', 'like', "%{$request->search}%")
                    ->orWhere('kategori_distribusi', 'like', "%{$request->search}%")
                    ->orWhere('status', 'like', "%{$request->search}%")
                    ->orWhere('id', $request->search);

                $q->orWhereHas('posko', function ($q2) use ($request) {
                    $q2->where('nama_posko', 'like', "%{$request->search}%");
                });
            });
        }

        $distribusi = $query->latest()->get();

        return view('management_distribusi.distribusi.index', compact('distribusi'));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('management_distribusi.distribusi.create', [
            'barangKeluar' => BarangKeluar::with('barang')->get(),
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

            // ✅ SIMPAN DATA UTAMA (JANGAN IKUTIN barang_detail)
            $distribusi = Distribusi::create(
                $request->except('barang_detail')
            );

            // ✅ SIMPAN DETAIL
            foreach ($request->barang_detail as $detail) {

                if (empty($detail['barang_keluar_id'])) continue;

                $barangKeluar = BarangKeluar::find($detail['barang_keluar_id']);
                if (!$barangKeluar) continue;

                if ($detail['jumlah_kirim'] > $barangKeluar->jumlah) {
                    throw new \Exception("Jumlah kirim melebihi stok barang keluar");
                }

                DetailDistribusi::create([
                    'distribusi_id' => $distribusi->id,
                    'barang_keluar_id' => $barangKeluar->id,
                    'jumlah_kirim' => $detail['jumlah_kirim'],
                    'satuan' => $detail['satuan'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.management_distribusi.distribusi.index')
                ->with('success', 'Data berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }

    // ================= SHOW =================
    public function show($id)
    {
        $distribusi = Distribusi::with([
            'detailDistribusis.barangKeluar.barang',
            'bencana',
            'posko.desa'
        ])->findOrFail($id);

        return view('management_distribusi.distribusi.show', compact('distribusi'));
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $distribusi = Distribusi::with([
            'detailDistribusis.barangKeluar.barang',
            'bencana',
            'posko.desa'
        ])->findOrFail($id);

        return view('management_distribusi.distribusi.edit', [
            'distribusi' => $distribusi,
            'barangKeluar' => BarangKeluar::with('barang')->get(),
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

            // ✅ UPDATE DATA UTAMA
            $distribusi->update(
                $request->except('barang_detail')
            );

            // ✅ HAPUS DETAIL LAMA
            $distribusi->detailDistribusis()->delete();

            // ✅ SIMPAN ULANG DETAIL
            foreach ($request->barang_detail as $detail) {

                if (empty($detail['barang_keluar_id'])) continue;

                $barangKeluar = BarangKeluar::find($detail['barang_keluar_id']);
                if (!$barangKeluar) continue;

                if ($detail['jumlah_kirim'] > $barangKeluar->jumlah) {
                    throw new \Exception("Jumlah kirim melebihi stok asli");
                }

                DetailDistribusi::create([
                    'distribusi_id' => $distribusi->id,
                    'barang_keluar_id' => $barangKeluar->id,
                    'jumlah_kirim' => $detail['jumlah_kirim'],
                    'satuan' => $detail['satuan'],
                ]);
            }

            DB::commit();

            return redirect()->route('management_distribusi.distribusi.index')
                ->with('success', 'Data berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
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