<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisBarang;

class JenisBarangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $data = JenisBarang::when($search, function ($query) use ($search) {
            $query->where('nama_jenis_barang', 'like', '%' . $search . '%');
        })->get();

        return view('jenis_barang.index', compact('data', 'search'));
    }

    public function create()
    {
        return view('jenis_barang.create');
    }

    public function store(Request $request)
    {
        JenisBarang::create([
            'id_jenis_barang' => 'JB' . rand(100,999),
            'nama_jenis_barang' => $request->nama_jenis_barang,
            'keterangan' => $request->keterangan
        ]);

        return redirect('/admin/jenis-barang')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = JenisBarang::findOrFail($id);
        return view('jenis_barang.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = JenisBarang::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('admin.jenis-barang.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $data = JenisBarang::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.jenis-barang.index')
            ->with('success', 'Data berhasil dihapus');
    }
}