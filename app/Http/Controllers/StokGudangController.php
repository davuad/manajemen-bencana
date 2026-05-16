<?php

namespace App\Http\Controllers;

use App\Models\StokGudang;
use App\Models\Gudang;
use App\Models\Barang; 
use Illuminate\Http\Request;

class StokGudangController extends Controller
{
    // ===============================
    // 📄 INDEX
    // ===============================
    public function index(Request $request)
    {
        $query = StokGudang::with(['gudang', 'barang']);

        if ($request->search) {
            $query->whereHas('gudang', function ($q) use ($request) {
                $q->where('nama_gudang', 'like', '%' . $request->search . '%');
            });
        }

        $stok = $query->latest()->paginate(5);

        return view('stok_gudang.index', compact('stok'));
    }

    // ===============================
    // ➕ CREATE
    // ===============================
    public function create()
    {
        return view('stok_gudang.create', [
            'gudang' => Gudang::all(),
            'barang' => Barang::all()
        ]);
    }

    // ===============================
    // 💾 STORE
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'gudang_id' => 'required|exists:gudang,id',
            'barang_id' => 'required|exists:barang,id_barang', // ✅ FIX
            'jumlah_stok' => 'required|integer|min:0',
            'kondisi_barang' => 'required|in:baik,rusak',
            'keterangan' => 'nullable|max:150'
        ]);

        StokGudang::create([
            'gudang_id' => $request->gudang_id,
            'barang_id' => $request->barang_id,
            'jumlah_stok' => $request->jumlah_stok,
            'kondisi_barang' => $request->kondisi_barang,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('stok_gudang.index')
            ->with('success', 'Stok berhasil ditambahkan');
    }

    // ===============================
    // ✏️ EDIT
    // ===============================
    public function edit($id)
    {
        return view('stok_gudang.edit', [
            'stok' => StokGudang::findOrFail($id),
            'gudang' => Gudang::all(),
            'barang' => Barang::all()
        ]);
    }

    // ===============================
    // 🔄 UPDATE
    // ===============================
    public function update(Request $request, $id)
    {
        $request->validate([
            'gudang_id' => 'required|exists:gudang,id',
            'barang_id' => 'required|exists:barang,id_barang', // ✅ FIX
            'jumlah_stok' => 'required|integer|min:0',
            'kondisi_barang' => 'required|in:baik,rusak',
            'keterangan' => 'nullable|max:150'
        ]);

        $stok = StokGudang::findOrFail($id);

        $stok->update([
            'gudang_id' => $request->gudang_id,
            'barang_id' => $request->barang_id,
            'jumlah_stok' => $request->jumlah_stok,
            'kondisi_barang' => $request->kondisi_barang,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('stok_gudang.index')
            ->with('success', 'Stok berhasil diupdate');
    }

    // ===============================
    // 🗑️ DELETE
    // ===============================
    public function destroy($id)
    {
        StokGudang::findOrFail($id)->delete();

        return redirect()->route('stok_gudang.index')
            ->with('success', 'Stok berhasil dihapus');
    }
}