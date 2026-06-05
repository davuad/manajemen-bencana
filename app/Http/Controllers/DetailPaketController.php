<?php

namespace App\Http\Controllers;

use App\Models\DetailDistribusi;
use Illuminate\Http\Request;

class DetailDistribusiController extends Controller
{
    // INDEX (VIEW)
    public function index()
    {
        $detail_distribusi = DetailDistribusi::with([
            'distribusi',
            'barangKeluar'
        ])->get();

        return view('management_distribusi.detail_distribusi.index', compact('detail_distribusi'));
    }

    // CREATE (FORM)
    public function create()
    {
        return view('management_distribusi.detail_distribusi.create');
    }

    // STORE
    public function store(Request $request)
    {
        $data = $request->validate([
            'distribusi_id' => 'required|integer',
            'barang_keluar_id' => 'required|integer',
            'jumlah' => 'required|integer',
            'satuan' => 'required|string|max:20',
            'keterangan' => 'nullable|string|max:100',
        ]);

        DetailDistribusi::create($data);

        return redirect()->route('detail_distribusi.index')
                         ->with('success', 'Data detail berhasil ditambahkan');
    }

    // SHOW (OPTIONAL)
    public function show($id)
    {
        $data = DetailDistribusi::with([
            'distribusi',
            'barangKeluar'
        ])->findOrFail($id);

        return view('management_distribusi.detail_distribusi.show', compact('data'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $detail = DetailDistribusi::findOrFail($id);
        $detail->update($request->all());

        return redirect()->route('detail_distribusi.index')
                         ->with('success', 'Data berhasil diupdate');
    }

    // DELETE
    public function destroy($id)
    {
        $detail = DetailDistribusi::findOrFail($id);
        $detail->delete();

        return redirect()->route('detail_distribusi.index')
                         ->with('success', 'Data berhasil dihapus');
    }
}