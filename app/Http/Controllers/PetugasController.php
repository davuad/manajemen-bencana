<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;
use App\Models\Posko;

class PetugasController extends Controller
{
    // INDEX
   // Tambahkan Request $request di dalam parameter index
public function index(Request $request)
{
    // Mulai query dengan eager loading relasi 'posko'
    $query = Petugas::with('posko');

    // Jika ada parameter search dari input teks
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('nama_petugas', 'like', "%$search%")
              ->orWhere('jabatan', 'like', "%$search%")
              ->orWhere('no_hp', 'like', "%$search%")
              ->orWhere('tahun', 'like', "%$search%");
              
            // Opsional: Jika ingin bisa mencari berdasarkan nama posko juga
            $q->orWhereHas('posko', function ($p) use ($search) {
                $p->where('nama_posko', 'like', "%$search%");
            });
        });
    }

    // Ambil data terbaru dengan paginasi 10 data
    $data = $query->latest()->paginate(10);

    // Kirim data ke view (bawa juga request search-nya agar paginasi tidak patah)
    return view('management_barang.petugas.index', compact('data'))->with('search', $request->search);
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

        return redirect()->route('admin.management_barang.petugas.index')
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

        return redirect()->route('admin.management_barang.petugas.index')
            ->with('success', 'Data petugas berhasil diupdate');
    }

    // DELETE
    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);
        $petugas->delete();

        return redirect()->route('admin.management_barang.petugas.index')
            ->with('success', 'Data petugas berhasil dihapus');
    }
}