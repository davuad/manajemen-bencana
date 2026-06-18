<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;
use App\Models\Posko;

class PetugasController extends Controller
{
    // INDEX
    public function index()
    {
        $data = Petugas::with('posko')->latest()->paginate(10);

        return view('management_barang.petugas.index', compact('data'));
    }

    // CREATE
    public function create()
    { 
        $posko = Posko::all();

        return view('management_barang.petugas.create', compact('posko'));
    }

    // STORE
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_petugas' => 'required|max:100',
            'jabatan' => 'required|in:Admin,Relawan,Koordinator',
            'no_hp' => 'required|max:15',
            'tahun' => 'required|digits:4',
            'status' => 'required|in:aktif,nonaktif',
            'posko_id' => 'required|exists:posko,id',
        ]);

        Petugas::create($validated);

        return redirect()->route('management_barang.petugas.index')
            ->with('success', 'Data petugas berhasil ditambahkan');
    }

    // SHOW
    public function show($id)
    {
        $petugas = Petugas::with('posko')->findOrFail($id);
        return view('management_barang.petugas.show', compact('petugas'));
    }

    // EDIT
    public function edit($id)
    {
        $petugas = Petugas::findOrFail($id);
        $posko = Posko::all();

        return view('management_barang.petugas.edit', compact('petugas', 'posko'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $petugas = Petugas::findOrFail($id);

        $validated = $request->validate([
            'nama_petugas' => 'required|max:100',
            'jabatan' => 'required|in:Admin,Relawan,Koordinator',
            'no_hp' => 'required|max:15',
            'tahun' => 'required|digits:4',
            'status' => 'required|in:aktif,nonaktif',
            'posko_id' => 'required|exists:posko,id',
        ]);

        $petugas->update($validated);

        return redirect()->route('management_barang.petugas.index')
            ->with('success', 'Data petugas berhasil diupdate');
    }

    // DELETE
    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);
        $petugas->delete();

        return redirect()->route('management_barang.petugas.index')
            ->with('success', 'Data petugas berhasil dihapus');
    }
}