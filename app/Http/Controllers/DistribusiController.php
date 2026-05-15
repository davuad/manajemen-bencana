<?php

namespace App\Http\Controllers;

use App\Models\Distribusi;
use App\Models\BarangKeluar;
use App\Models\Bencana;
use App\Models\Posko;
use Illuminate\Http\Request;

class DistribusiController extends Controller
{
    // INDEX
    public function index(Request $request)
{
    $query = Distribusi::with([
        'barangKeluar',
        'bencana',
        'posko'
    ]);

    // 🔍 SEARCH
    if ($request->search) {
        $query->where(function ($q) use ($request) {

            // 🔹 field di tabel distribusi
            $q->where('lokasi_distribusi', 'like', '%' . $request->search . '%')
              ->orWhere('kendaraan', 'like', '%' . $request->search . '%')
              ->orWhere('nama_supir', 'like', '%' . $request->search . '%')
              ->orWhere('nomor_kendaraan', 'like', '%' . $request->search . '%')
              ->orWhere('kategori_distribusi', 'like', '%' . $request->search . '%')
              ->orWhere('status', 'like', '%' . $request->search . '%')
              ->orWhere('id', $request->search);

            // 🔹 relasi posko (INI pengganti nama_posko kamu tadi)
            $q->orWhereHas('posko', function ($q2) use ($request) {
                $q2->where('nama_posko', 'like', '%' . $request->search . '%');
            });

            // 🔹 relasi bencana
            $q->orWhereHas('bencana', function ($q2) use ($request) {
                $q2->where('id', $request->search);
            });

        });
    }

    $distribusi = $query->get();

    return view('management_distribusi.distribusi.index', compact('distribusi'));
}

    // CREATE
    public function create()
    {
        $barangKeluar = BarangKeluar::all();
        $bencana = Bencana::all();
        $posko = Posko::all();

        return view('management_distribusi.distribusi.create', compact(
            'barangKeluar',
            'bencana',
            'posko'
        ));
    }

    // STORE
    public function store(Request $request)
    {
        $data = $request->validate([
            'barang_keluar_id' => 'required|integer',
            'bencana_id' => 'required|integer',
            'posko_id' => 'required|integer',
            'tanggal_distribusi' => 'required|date',
            'lokasi_distribusi' => 'required|string|max:100',
            'kendaraan' => 'required|string|max:100',
            'nama_supir' => 'required|string|max:100',
            'nomor_kendaraan' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:255',
            'kategori_distribusi' => 'required|string|max:50',
            'status' => 'required|string|max:20',
        ]);

        Distribusi::create($data);

        return redirect()->route('management_distribusi.distribusi.index')
            ->with('success', 'Data berhasil disimpan');
    }

    // SHOW (FIX ERROR)
    public function show($id)
    {
        $distribusi = Distribusi::with([
            'barangKeluar',
            'bencana',
            'posko',
            'detailDistribusis'
        ])->findOrFail($id);

        return view('management_distribusi.distribusi.show', compact('distribusi'));
    }

    // EDIT
    public function edit($id)
    {
        $distribusi = Distribusi::findOrFail($id);
        $barangKeluar = BarangKeluar::all();
        $bencana = Bencana::all();
        $posko = Posko::all();

        return view('management_distribusi.distribusi.edit', compact(
            'distribusi',
            'barangKeluar',
            'bencana',
            'posko'
        ));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $distribusi = Distribusi::findOrFail($id);

        $data = $request->validate([
            'barang_keluar_id' => 'nullable|integer',
            'bencana_id' => 'nullable|integer',
            'posko_id' => 'nullable|integer',
            'tanggal_distribusi' => 'required|date',
            'lokasi_distribusi' => 'required|string|max:100',
            'kendaraan' => 'required|string|max:100',
            'nama_supir' => 'required|string|max:100',
            'nomor_kendaraan' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:255',
            'kategori_distribusi' => 'required|string|max:50',
            'status' => 'required|string|max:20',
        ]);

        $distribusi->update($data);

        return redirect()->route('management_distribusi.distribusi.index')
            ->with('success', 'Data berhasil diupdate');
    }

    // DELETE
    public function destroy($id)
    {
        Distribusi::findOrFail($id)->delete();

        return redirect()->route('management_distribusi.distribusi.index');
    }
}