<?php

namespace App\Http\Controllers;

use App\Models\StokPosko;
use App\Models\Posko;
use App\Models\Barang;
use Illuminate\Http\Request;

class StokPoskoController extends Controller
{
    public function index()
    {
        $data = StokPosko::with(['posko', 'barang'])->get();
        return view('manajemen_barang.stok_posko.index', compact('data'));
    }

    public function create()
    {
        $posko = Posko::all();
        $barang = Barang::all();

        return view('manajemen_barang.stok_posko.create', compact('posko', 'barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'posko_id' => 'required',
            'barang_id' => 'required',
            'kategori_distribusi' => 'required|in:bencana,pasca_bencana',
            'jumlah_barang' => 'required|integer',
            'satuan' => 'required',
            'tanggal_masuk' => 'required|date',
            'keterangan' => 'nullable'
        ]);

        StokPosko::create($request->all());

        return redirect()->route('manajemen_barang.stok_posko.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function show($id)
    {
        $data = StokPosko::with(['posko', 'barang'])->findOrFail($id);
        return view('manajemen_barang.stok_posko.show', compact('data'));
    }

    public function edit($id)
    {
        $data = StokPosko::findOrFail($id);
        $posko = Posko::all();
        $barang = Barang::all();

        return view('manajemen_barang.stok_posko.edit', compact('data', 'posko', 'barang'));
    }

    public function update(Request $request, $id)
    {
        $data = StokPosko::findOrFail($id);

        $request->validate([
            'posko_id' => 'required',
            'barang_id' => 'required',
            'kategori_distribusi' => 'required|in:bencana,pasca_bencana',
            'jumlah_barang' => 'required|integer',
            'satuan' => 'required',
            'tanggal_masuk' => 'required|date',
            'keterangan' => 'nullable'
        ]);

        $data->update($request->all());

        return redirect()->route('manajemen_barang.stok_posko.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $data = StokPosko::findOrFail($id);
        $data->delete();

        return redirect()->route('manajemen_barang.stok_posko.index')
            ->with('success', 'Data berhasil dihapus');
    }
}