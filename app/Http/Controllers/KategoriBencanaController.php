<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriBencana;

class KategoriBencanaController extends Controller
{
    public function index(Request $request)
    {
        $query = KategoriBencana::query();

        if ($request->search) {
            $query->where('nama_kategori', 'like', '%' . $request->search . '%');
        }

        $kategori = $query->latest()->get();

        return view('kategori_bencana.index', compact('kategori'));
    }

    public function create()
    {
        return view('kategori_bencana.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|max:50',
            'deskripsi' => 'nullable|max:150',
        ]);

        KategoriBencana::create($request->all());

        return redirect()->route('admin.kategori_bencana.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kategori = KategoriBencana::findOrFail($id);
        return view('kategori_bencana.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|max:50',
            'deskripsi' => 'nullable|max:150',
        ]);

        $kategori = KategoriBencana::findOrFail($id);
        $kategori->update($request->all());

        return redirect()->route('admin.kategori_bencana.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $kategori = KategoriBencana::findOrFail($id);
        $kategori->delete();

        return redirect()->route('admin.kategori_bencana.index')
            ->with('success', 'Data berhasil dihapus');
    }
}
