<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\DetailPaket;
use App\Models\DistribusiPaket;
use App\Models\PaketBantuan;
use App\Models\Pegawai;
use App\Models\StokPosko;
use App\Models\WargaTerdampak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistribusiPaketController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->search;
        $desaId = $request->desa_id;

        $desaList = Desa::orderBy('nama_desa', 'asc')->get();

        $warga = WargaTerdampak::with(['desa', 'bencana'])
            ->where('jenis_bantuan', 'Bantuan Pasca Bencana')
            ->where('status_penyaluran', 'Belum diproses')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('no_kk', 'like', '%' . $search . '%')
                        ->orWhere('nama_kepala_keluarga', 'like', '%' . $search . '%');
                });
            })
            ->when($desaId, function ($query) use ($desaId) {
                $query->where('desa_id', $desaId);
            })
            ->latest()
            ->paginate(5, ['*'], 'warga_page');

        $riwayatDistribusi = DistribusiPaket::with([
            'wargaTerdampak.desa',
            'wargaTerdampak.bencana',
            'paketBantuan',
            'pegawai'
        ])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('wargaTerdampak', function ($q) use ($search) {
                    $q->where('no_kk', 'like', '%' . $search . '%')
                        ->orWhere('nama_kepala_keluarga', 'like', '%' . $search . '%');
                });
            })
            ->when($desaId, function ($query) use ($desaId) {
                $query->whereHas('wargaTerdampak', function ($q) use ($desaId) {
                    $q->where('desa_id', $desaId);
                });
            })
            ->latest()
            ->paginate(5, ['*'], 'riwayat_page');

        return view('management_distribusi.distribusi_paket.index', compact(
            'warga',
            'riwayatDistribusi',
            'desaList',
            'search',
            'desaId'
        ));
    }

    public function create(Request $request)
    {
        $warga = WargaTerdampak::with(['desa', 'bencana'])
            ->findOrFail($request->warga_id);

        $paketBantuan = PaketBantuan::where('status', 'aktif')
            ->whereHas('posko', function ($q) use ($warga) {
                $q->where('desa_id', $warga->desa_id);
            })
            ->get();

        $pegawai = Pegawai::all();

        return view('management_distribusi.distribusi_paket.create', compact('warga', 'paketBantuan', 'pegawai'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warga_terdampak_id' => 'required|exists:warga_terdampak,id',
            'paket_bantuan_id'   => 'required|exists:paket_bantuan,id',
            'jumlah_paket'       => 'required|integer|min:1',
            'tanggal_distribusi' => 'required|date',
            'pegawai_id'         => 'required|exists:pegawai,id_pegawai',
        ]);

        DB::beginTransaction();

        try {
            $paket = PaketBantuan::findOrFail($validated['paket_bantuan_id']);

            $detailPaket = DetailPaket::with('barang')
                ->where('paket_bantuan_id', $validated['paket_bantuan_id'])
                ->get();

            if ($detailPaket->isEmpty()) {
                throw new \Exception('Detail paket bantuan belum tersedia.');
            }

            $stokKurang = [];

            // 1. Cek semua stok dulu
            foreach ($detailPaket as $item) {
                $kebutuhan = $item->jumlah * $validated['jumlah_paket'];

                $stok = StokPosko::where('barang_id', $item->barang_id)
                    ->where('posko_id', $paket->posko_id)
                    ->where('kategori_distribusi', 'pasca_bencana')
                    ->first();

                $namaBarang = $item->barang->nama_barang ?? $item->barang_id;

                if (!$stok) {
                    $stokKurang[] = $namaBarang . ' (stok tidak ditemukan)';
                    continue;
                }

                if ($stok->jumlah_barang < $kebutuhan) {
                    $stokKurang[] = $namaBarang . ' (dibutuhkan: ' . $kebutuhan . ', tersedia: ' . $stok->jumlah_barang . ')';
                }
            }

            // 2. Kalau ada yang kurang, hentikan proses
            if (!empty($stokKurang)) {
                throw new \Exception('Stok tidak cukup untuk: ' . implode(', ', $stokKurang));
            }

            // 3. Kalau semua cukup, baru kurangi stok
            foreach ($detailPaket as $item) {
                $kebutuhan = $item->jumlah * $validated['jumlah_paket'];

                $stok = StokPosko::where('barang_id', $item->barang_id)
                    ->where('posko_id', $paket->posko_id)
                    ->where('kategori_distribusi', 'pasca_bencana')
                    ->first();

                $stok->decrement('jumlah_barang', $kebutuhan);
            }

            // 4. Simpan distribusi paket
            DistribusiPaket::create([
                'warga_terdampak_id' => $validated['warga_terdampak_id'],
                'paket_bantuan_id'   => $validated['paket_bantuan_id'],
                'jumlah_paket'       => $validated['jumlah_paket'],
                'tanggal_distribusi' => $validated['tanggal_distribusi'],
                'pegawai_id'         => $validated['pegawai_id'],
                'status_distribusi'  => 'Proses Penyaluran',
            ]);

            // 5. Update status warga
            WargaTerdampak::where('id', $validated['warga_terdampak_id'])->update([
                'status_penyaluran' => 'Proses Penyaluran',
            ]);

            DB::commit();

            return redirect()
                ->route('management_distribusi.distribusi_paket.index')
                ->with('success', 'Distribusi berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->withInput()->with('error', $e->getMessage());
        }
    }
    public function show(int $id)
    {
        $distribusi = DistribusiPaket::with([
            'wargaTerdampak.desa',
            'wargaTerdampak.bencana',
            'paketBantuan.detailPaket.barang',
            'pegawai'
        ])->findOrFail($id);

        return view('management_distribusi.distribusi_paket.show', compact('distribusi'));
    }

    public function selesai(int $id)
    {
        $distribusi = DistribusiPaket::findOrFail($id);

        DB::beginTransaction();

        try {
            $distribusi->update([
                'status_distribusi' => 'Sudah disalurkan'
            ]);

            WargaTerdampak::where('id', $distribusi->warga_terdampak_id)->update([
                'status_penyaluran' => 'Sudah disalurkan',
                'tanggal_penyaluran' => now()
            ]);

            DB::commit();

            return back()->with('success', 'Distribusi berhasil diselesaikan.');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Gagal menyelesaikan distribusi.');
        }
    }
}
