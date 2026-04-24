<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\PengaduanBencana;
use App\Models\Posko;
use Illuminate\Http\Request;

class PoskoController extends Controller
{
    public function index(Request $request)
    {
        $query = Posko::with(['desa', 'pengaduan']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_posko', 'like', '%' . $request->search . '%')
                    ->orWhere('id', $request->search);
            });
        }

        if ($request->desa) {
            $query->where('desa_id', $request->desa);
        }

        $posko = $query->orderBy('id', 'asc')->paginate(5);

        $desa = \App\Models\Desa::all();

        return view('management_posko.posko.index', compact('posko', 'desa'));
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
            'lokasi' => 'required',
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
            'status' => 'required|in:aktif,tidak aktif'
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
