<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\PengaduanBencana;
use App\Models\Posko;
use Illuminate\Http\Request;

class PoskoController extends Controller
{
    public function index()
    {
        $posko = Posko::with(['desa', 'pengaduan'])->get();
        return view('management_posko.posko.index', compact('posko'));
    }

    public function create()
    {
        $desa = Desa::all();
        $pengaduan = PengaduanBencana::where('status_pengaduan', '!=', 'SELESAI')->get();

        return view('management_posko.posko.create', compact('desa', 'pengaduan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_posko' => 'required|max:100',
            'tanggal_dibuat' => 'required|date',
            'desa_id' => 'required|exists:desa,id',
            'pengaduan_bencana_id' => 'required|exists:pengaduan_bencana,id',
            'lokasi' => 'required'
        ]);

        Posko::create([
            'nama_posko' => $request->nama_posko,
            'tanggal_dibuat' => $request->tanggal_dibuat,
            'desa_id' => $request->desa_id,
            'pengaduan_bencana_id' => $request->pengaduan_bencana_id,
            'lokasi' => $request->lokasi,
            'status' => 'aktif'
        ]);

        return redirect()->route('management_posko.posko.index')
            ->with('success', 'Data posko berhasil ditambahkan');
    }

    public function edit($id)
    {
        $posko = Posko::findOrFail($id);
        $desa = Desa::all();
        $pengaduan = PengaduanBencana::where('status_pengaduan', '!=', 'SELESAI')->get();

        return view('management_posko.posko.edit', compact('posko', 'desa', 'pengaduan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_posko' => 'required|max:100',
            'tanggal_dibuat' => 'required|date',
            'desa_id' => 'required|exists:desa,id',
            'pengaduan_bencana_id' => 'required|exists:pengaduan_bencana,id',
            'lokasi' => 'required',
            'status' => 'required|in:aktif,tidak_aktif' // validasi radio button
        ]);

        $posko = Posko::findOrFail($id);

        $posko->update([
            'nama_posko' => $request->nama_posko,
            'tanggal_dibuat' => $request->tanggal_dibuat,
            'desa_id' => $request->desa_id,
            'pengaduan_bencana_id' => $request->pengaduan_bencana_id,
            'lokasi' => $request->lokasi,
            'status' => $request->status
        ]);

        return redirect()->route('management_posko.posko.index')
            ->with('success', 'Data posko berhasil diperbarui');
    }

    public function destroy($id)
    {
        $posko = Posko::findOrFail($id);
        $posko->delete();

        return redirect()->route('management_posko.posko.index')
            ->with('success', 'Data posko berhasil dihapus');
    }
}
