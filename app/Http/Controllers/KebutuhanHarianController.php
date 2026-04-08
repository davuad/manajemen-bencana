<?php

namespace App\Http\Controllers;

use App\Models\DapurUmum;
use App\Models\KebutuhanHarian;
use Illuminate\Http\Request;

class KebutuhanHarianController extends Controller
{
    public function index()
    {
        $kebutuhan = KebutuhanHarian::with('dapur_umum')->get();

        return view('management_posko.kebutuhan_harian.index', compact('kebutuhan'));
    }

    public function create()
    {
        $dapur = DapurUmum::all();

        return view('management_posko.kebutuhan_harian.create', compact('dapur'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jumlah_warga' => 'required|integer',
            'porsi_per_orang' => 'required|integer',
            'total_porsi' => 'required|integer',
            'dapur_umum_id' => 'required|exists:dapur_umum,id',
        ]);

        KebutuhanHarian::create($validated);

        return redirect()->route('management_posko.kebutuhan_harian.index');
    }

    public function edit($id)
    {
        $kebutuhan = KebutuhanHarian::findOrFail($id);
        $dapur = DapurUmum::all();

        return view('management_posko.kebutuhan_harian.edit', compact('kebutuhan', 'dapur'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jumlah_warga' => 'required|integer',
            'porsi_per_orang' => 'required|integer',
            'total_porsi' => 'required|integer',
            'dapur_umum_id' => 'required|exists:dapur_umum,id',
        ]);

        $kebutuhan = KebutuhanHarian::findOrFail($id);
        $kebutuhan->update($validated);

        return redirect()->route('management_posko.kebutuhan_harian.index')
            ->with('success', 'Data kebutuhan harian berhasil diupdate');
    }

    public function destroy($id)
    {
        $kebutuhan = KebutuhanHarian::findOrFail($id);
        $kebutuhan->delete();

        return redirect()->route('management_posko.kebutuhan_harian.index')
            ->with('success', 'Data kebutuhan harian berhasil dihapus');
    }
}
