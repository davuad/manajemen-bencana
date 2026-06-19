<?php

namespace App\Http\Controllers;

use App\Models\DetailDistribusi;
use Illuminate\Http\Request;

class DetailDistribusiController extends Controller
{
    // ================= INDEX =================
    public function index()
    {
        $detail_distribusi = DetailDistribusi::with([
            'distribusi',
            'detailBarangKeluar.barang'
        ])->get();

        return view(
            'management_distribusi.detail_distribusi.index',
            compact('detail_distribusi')
        );
    }

    // ================= CREATE =================
    public function create()
    {
        return view('management_distribusi.detail_distribusi.create');
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $data = $request->validate([
            'distribusi_id'            => 'required|exists:distribusi,id',
            'detail_barang_keluar_id'  => 'required|exists:detail_barang_keluar,id',
            'jumlah_kirim'             => 'required|integer|min:1',
            'satuan'                   => 'required|string|max:20',
        ]);

        DetailDistribusi::create($data);

        return redirect()
            ->route('admin.management_distribusi.detail_distribusi.index')
            ->with('success', 'Data detail distribusi berhasil ditambahkan.');
    }

    // ================= SHOW =================
    public function show($id)
    {
        $data = DetailDistribusi::with([
            'distribusi',
            'detailBarangKeluar.barang'
        ])->findOrFail($id);

        return view(
            'management_distribusi.detail_distribusi.show',
            compact('data')
        );
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $detail = DetailDistribusi::findOrFail($id);

        $data = $request->validate([
            'distribusi_id'            => 'required|exists:distribusi,id',
            'detail_barang_keluar_id'  => 'required|exists:detail_barang_keluar,id',
            'jumlah_kirim'             => 'required|integer|min:1',
            'satuan'                   => 'required|string|max:20',
        ]);

        $detail->update($data);

        return redirect()
            ->route('admin.management_distribusi.detail_distribusi.index')
            ->with('success', 'Data detail distribusi berhasil diupdate.');
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        $detail = DetailDistribusi::findOrFail($id);
        $detail->delete();

        return redirect()
            ->route('admin.management_distribusi.detail_distribusi.index')
            ->with('success', 'Data detail distribusi berhasil dihapus.');
    }
}