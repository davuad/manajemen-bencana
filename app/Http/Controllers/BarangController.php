<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\JenisBarang;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $data = Barang::with('jenis')
            ->when($search, function ($query) use ($search) {
                $query->where('id_barang', 'like', "%{$search}%")
                    ->orWhere('nama_barang', 'like', "%{$search}%");
            })
            ->get();

        return view('barang.index', compact('data', 'search'));
    }

    public function create()
    {
        $jenis = JenisBarang::all();
        return view('barang.create', compact('jenis'));
    }

    public function store(Request $request)
    {
        Barang::create([
            'id_barang' => 'BR' . rand(100,999),
            'nama_barang' => $request->nama_barang,
            'id_jenis_barang' => $request->id_jenis_barang,
            'stok' => $request->stok,
            'satuan' => $request->satuan,
            'keterangan' => $request->keterangan
        ]);

        return redirect('/barang')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = Barang::findOrFail($id);
        $jenis = JenisBarang::all();

        return view('barang.edit', compact('data', 'jenis'));
    }

    public function update(Request $request, $id)
    {
        $data = Barang::findOrFail($id);
        $data->update($request->all());

        return redirect('/barang')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $data = Barang::findOrFail($id);
        $data->delete();

        return redirect('/barang')->with('success', 'Data berhasil dihapus');
    }
}