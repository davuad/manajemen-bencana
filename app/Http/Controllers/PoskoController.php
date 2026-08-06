<?php

namespace App\Http\Controllers;

use App\Models\Bencana;
use App\Models\Desa;
use App\Models\PengaduanBencana;
use App\Models\Posko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $desa = Desa::all();
        $bencana = Bencana::all();

        return view('management_posko.posko.index', compact('posko', 'desa', 'bencana'));
    }

    public function create($role)
    {
        $desa = Desa::all();
        $bencana = Bencana::all();
        $pengaduan = PengaduanBencana::where('status_pengaduan', '!=', 'SELESAI')->get();

        return view('management_posko.posko.create', compact('desa', 'pengaduan', 'bencana'));
    }

    public function store(Request $request, $role)
    {
        $request->validate([
            'nama_posko' => 'required|max:100',
            'tanggal_dibuat' => 'required|date',
            'desa_id' => 'required|exists:desa,id',
            'bencana_id' => 'required|exists:bencana,id',
            'pengaduan_bencana_id' => 'required|exists:pengaduan_bencana,id',
            'lokasi' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $foto = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('posko', 'public');
        }

        Posko::create([
            'nama_posko' => $request->nama_posko,
            'tanggal_dibuat' => $request->tanggal_dibuat,
            'desa_id' => $request->desa_id,
            'bencana_id' => $request->bencana_id,
            'pengaduan_bencana_id' => $request->pengaduan_bencana_id,
            'lokasi' => $request->lokasi,
            'status' => 'aktif',
            'foto' => $foto,
        ]);

        return redirect()->route('management_posko.posko.index', ['role' => $role])
            ->with('success', 'Data posko berhasil ditambahkan');
    }

    public function edit($role, $id)
    {
        $posko = Posko::findOrFail($id);
        $desa = Desa::all();
        $bencana = Bencana::all();
        $pengaduan = PengaduanBencana::where('status_pengaduan', '!=', 'SELESAI')->get();

        return view('management_posko.posko.edit', compact('posko', 'desa', 'pengaduan', 'bencana'));
    }

    public function update(Request $request, $role, $id)
    {
        $request->validate([
            'nama_posko' => 'required|max:100',
            'tanggal_dibuat' => 'required|date',
            'desa_id' => 'required|exists:desa,id',
            'bencana_id' => 'required|exists:bencana,id',
            'pengaduan_bencana_id' => 'required|exists:pengaduan_bencana,id',
            'lokasi' => 'required',
            'status' => 'required|in:aktif,tidak aktif',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $posko = Posko::findOrFail($id);

        $foto = $posko->foto;

        if ($request->hasFile('foto')) {
            if ($posko->foto && Storage::disk('public')->exists($posko->foto)) {
                Storage::disk('public')->delete($posko->foto);
            }

            $foto = $request->file('foto')->store('posko', 'public');
        }

        $posko->update([
            'nama_posko' => $request->nama_posko,
            'tanggal_dibuat' => $request->tanggal_dibuat,
            'desa_id' => $request->desa_id,
            'bencana_id' => $request->bencana_id,
            'pengaduan_bencana_id' => $request->pengaduan_bencana_id,
            'lokasi' => $request->lokasi,
            'status' => $request->status,
            'foto' => $foto,
        ]);

        return redirect()->route('management_posko.posko.index', ['role' => $role])
            ->with('success', 'Data posko berhasil diperbarui');
    }

    public function destroy($role, $id)
    {
        $posko = Posko::findOrFail($id);

        if ($posko->foto && Storage::disk('public')->exists($posko->foto)) {
            Storage::disk('public')->delete($posko->foto);
        }

        $posko->delete();

        return redirect()->route('management_posko.posko.index', ['role' => $role])
            ->with('success', 'Data posko berhasil dihapus');
    }
}
