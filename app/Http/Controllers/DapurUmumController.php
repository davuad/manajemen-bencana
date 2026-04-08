<?php

namespace App\Http\Controllers;

use App\Models\DapurUmum;
use App\Models\Posko;
use Illuminate\Http\Request;

class DapurUmumController extends Controller
{
    public function index()
    {
        $dapur = DapurUmum::with('posko')->get();

        return view('management_posko.dapur_umum.index', compact('dapur'));
    }

    public function create()
    {
        $posko = Posko::all();

        return view('management_posko.dapur_umum.create', compact('posko'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'posko_id' => 'required|exists:posko,id',
            'nama_dapur_umum' => 'required|max:100',
            'kapasitas_warga' => 'required|integer',
            'jumlah_warga' => 'required|integer',
            'penanggung_jawab' => 'required|max:100',
        ]);

        DapurUmum::create($validated);

        return redirect()->route('management_posko.dapur_umum.index');
    }

    public function edit($id)
    {
        $dapur = DapurUmum::findOrFail($id);
        $posko = Posko::all();

        return view('management_posko.dapur_umum.edit', compact('dapur', 'posko'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'posko_id' => 'required|exists:posko,id',
            'kapasitas_warga' => 'required|integer',
            'jumlah_warga' => 'required|integer',
            'penanggung_jawab' => 'required|max:100',
        ]);

        $dapur = DapurUmum::findOrFail($id);
        $dapur->update($validated);

        return redirect()->route('management_posko.dapur_umum.index')
            ->with('success', 'Data dapur umum berhasil diupdate');
    }

    public function destroy($id)
    {
        $dapur = DapurUmum::findOrFail($id);
        $dapur->delete();

        return redirect()->route('management_posko.dapur_umum.index')
            ->with('success', 'Data dapur umum berhasil dihapus');
    }
}
