<?php

namespace App\Http\Controllers;

use App\Models\DetailPaket;
use App\Models\PaketBantuan;
use App\Models\StokPosko;
use Illuminate\Http\Request;

class DetailPaketController extends Controller
{
    public function index(Request $request)
    {
        $paket_bantuan_id = $request->paket_bantuan_id;

        $paket_bantuan = PaketBantuan::with('posko')->findOrFail($paket_bantuan_id);

        $detail_paket = DetailPaket::with('barang')
            ->where('paket_bantuan_id', $paket_bantuan_id)
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('barang', function ($q) use ($request) {
                    $q->where('nama_barang', 'like', '%' . $request->search . '%');
                });
            })
            ->orderBy('id', 'asc')
            ->paginate(5);

        return view('management_distribusi.detail_paket.index', compact('paket_bantuan', 'detail_paket'));
    }

    public function create(Request $request)
    {
        $paket_bantuan_id = $request->paket_bantuan_id;

        $paket_bantuan = PaketBantuan::with('posko')->findOrFail($paket_bantuan_id);

        $stok_barang = StokPosko::with('barang')
            ->where('posko_id', $paket_bantuan->posko_id)
            ->where('kategori_distribusi', 'pasca_bencana')
            ->where('jumlah_barang', '>', 0)
            ->get();

        return view('management_distribusi.detail_paket.create', compact('paket_bantuan', 'stok_barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paket_bantuan_id' => 'required|exists:paket_bantuan,id',
            'barang_id' => 'required|exists:barang,id_barang',
            'jumlah' => 'required|integer|min:1',
        ]);

        $paket = PaketBantuan::findOrFail($request->paket_bantuan_id);

        $stok = StokPosko::where('posko_id', $paket->posko_id)
            ->where('barang_id', $request->barang_id)
            ->where('kategori_distribusi', 'pasca_bencana')
            ->first();

        if (!$stok) {
            return back()->withErrors([
                'barang_id' => 'Barang tidak tersedia pada stok posko untuk kategori pasca_bencana.'
            ])->withInput();
        }

        if ($request->jumlah > $stok->jumlah_barang) {
            return back()->withErrors([
                'jumlah' => 'Jumlah melebihi stok barang yang tersedia di posko.'
            ])->withInput();
        }

        $cekDetail = DetailPaket::where('paket_bantuan_id', $request->paket_bantuan_id)
            ->where('barang_id', $request->barang_id)
            ->first();

        if ($cekDetail) {
            return back()->withErrors([
                'barang_id' => 'Barang sudah ada di paket ini.'
            ])->withInput();
        }

        DetailPaket::create([
            'paket_bantuan_id' => $request->paket_bantuan_id,
            'barang_id' => $request->barang_id,
            'jumlah' => $request->jumlah,
        ]);

        $prefix = request()->segment(1);

        return redirect()->route($prefix . '.management_distribusi.detail_paket.index', [
            'paket_bantuan_id' => $request->paket_bantuan_id
        ])->with('success', 'Detail paket berhasil ditambahkan');
    }

    public function edit(int $id)
    {
        $detail_paket = DetailPaket::with(['paketBantuan.posko', 'barang'])->findOrFail($id);

        $stok_barang = StokPosko::with('barang')
            ->where('posko_id', $detail_paket->paketBantuan->posko_id)
            ->where('kategori_distribusi', 'pasca_bencana')
            ->where('jumlah_barang', '>', 0)
            ->get();

        return view('management_distribusi.detail_paket.edit', compact('detail_paket', 'stok_barang'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'paket_bantuan_id' => 'required|exists:paket_bantuan,id',
            'barang_id' => 'required|exists:barang,id_barang',
            'jumlah' => 'required|integer|min:1',
        ]);

        $detail_paket = DetailPaket::findOrFail($id);
        $paket = PaketBantuan::findOrFail($request->paket_bantuan_id);

        $stok = StokPosko::where('posko_id', $paket->posko_id)
            ->where('barang_id', $request->barang_id)
            ->where('kategori_distribusi', 'pasca_bencana')
            ->first();

        if (!$stok) {
            return back()->withErrors([
                'barang_id' => 'Barang tidak tersedia pada stok posko untuk kategori pasca_bencana.'
            ])->withInput();
        }

        if ($request->jumlah > $stok->jumlah_barang) {
            return back()->withErrors([
                'jumlah' => 'Jumlah melebihi stok barang yang tersedia di posko.'
            ])->withInput();
        }

        $cekDetail = DetailPaket::where('paket_bantuan_id', $request->paket_bantuan_id)
            ->where('barang_id', $request->barang_id)
            ->where('id', '!=', $id)
            ->first();

        if ($cekDetail) {
            return back()->withErrors([
                'barang_id' => 'Barang sudah ada di paket ini.'
            ])->withInput();
        }

        $detail_paket->update([
            'paket_bantuan_id' => $request->paket_bantuan_id,
            'barang_id' => $request->barang_id,
            'jumlah' => $request->jumlah,
        ]);

        $prefix = request()->segment(1);

        return redirect()->route($prefix . '.management_distribusi.detail_paket.index', [
            'paket_bantuan_id' => $request->paket_bantuan_id
        ])->with('success', 'Detail paket berhasil diperbarui');
    }

    public function destroy(int $id)
    {
        $detail_paket = DetailPaket::findOrFail($id);

        $paket_bantuan_id = $detail_paket->paket_bantuan_id;

        $detail_paket->delete();

        $prefix = request()->segment(1);

        return redirect()->route($prefix . '.management_distribusi.detail_paket.index', [
            'paket_bantuan_id' => $paket_bantuan_id
        ])->with('success', 'Detail paket berhasil dihapus');
    }
}
