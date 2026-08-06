<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SumberBarangMasuk;

class SumberBarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $data = SumberBarangMasuk::when($search, function ($query) use ($search) {
            $query->where('nama_sumber', 'like', '%' . $search . '%');
        })->get();

        return view('sumber_barang.index', compact('data', 'search'));
    }

    public function create()
    {
        return view('sumber_barang.create');
    }

    public function store(Request $request)
    {
        SumberBarangMasuk::create([
            'id_sumber' => 'SB' . rand(100,999),
            'nama_sumber' => $request->nama_sumber,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('admin.sumber-barang.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = SumberBarangMasuk::findOrFail($id);
        return view('sumber_barang.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = SumberBarangMasuk::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('admin.sumber-barang.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $data = SumberBarangMasuk::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.sumber-barang.index')
            ->with('success', 'Data berhasil dihapus');
    }
}