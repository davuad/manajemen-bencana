<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnakTerpisah;
use Illuminate\Support\Facades\Storage;

class AnakTerpisahController extends Controller
{
    // Tampilkan semua data anak terpisah
    public function index(Request $request)
    {
        $query = AnakTerpisah::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_anak', 'like', '%' . $search . '%')
                    ->orWhere('nama_ortu_wali', 'like', '%' . $search . '%')
                    ->orWhere('lokasi_ditemukan', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('filter_umur')) {
            $range = explode('-', $request->filter_umur);

            if (count($range) == 2) {
                $query->whereBetween('umur', [$range[0], $range[1]]);
            }
        }

        $data = $query->get();

        return view('anak_terpisah.index', compact('data'));
    }

    // Form tambah data
    public function create()
    {
        return view('anak_terpisah.create');
    }

    // Simpan data
    public function store(Request $request)
    {
        $request->validate([
            'nama_anak' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'umur' => 'nullable|integer',
            'tanggal_lahir' => 'nullable|date',
            'alamat_asal' => 'nullable|string',
            'lokasi_ditemukan' => 'required|string',
            'tanggal_ditemukan' => 'required|date',
            'nama_ortu_wali' => 'nullable|string',
            'kontak_keluarga' => 'nullable|string',
            'status_anak' => 'required|in:belum_dijemput,sudah_dijemput,dalam_proses',
            'foto_anak' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $foto = $request->file('foto_anak')->store('foto_anak', 'public');

        AnakTerpisah::create([
            'nama_anak' => $request->nama_anak,
            'jenis_kelamin' => $request->jenis_kelamin,
            'umur' => $request->umur,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat_asal' => $request->alamat_asal,
            'lokasi_ditemukan' => $request->lokasi_ditemukan,
            'tanggal_ditemukan' => $request->tanggal_ditemukan,
            'nama_ortu_wali' => $request->nama_ortu_wali,
            'kontak_keluarga' => $request->kontak_keluarga,
            'status_anak' => $request->status_anak,
            'foto_anak' => $foto,
            'bencana_id' => $request->bencana_id,
        ]);

        return redirect()
            ->route('admin.anak_terpisah.index')
            ->with('success', 'Data berhasil disimpan');
    }

    // Detail data
    public function show($id)
    {
        $anak = AnakTerpisah::findOrFail($id);

        return view('anak_terpisah.show', compact('anak'));
    }

    // Form edit
    public function edit($id)
    {
        $anak = AnakTerpisah::findOrFail($id);

        return view('anak_terpisah.edit', compact('anak'));
    }

    // Update data
    public function update(Request $request, $id)
    {
        $anak = AnakTerpisah::findOrFail($id);

        $request->validate([
            'nama_anak' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'umur' => 'nullable|integer',
            'tanggal_lahir' => 'nullable|date',
            'alamat_asal' => 'nullable|string',
            'lokasi_ditemukan' => 'required|string',
            'tanggal_ditemukan' => 'required|date',
            'nama_ortu_wali' => 'nullable|string',
            'kontak_keluarga' => 'nullable|string',
            'status_anak' => 'required|in:belum_dijemput,sudah_dijemput,dalam_proses',
            'foto_anak' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('foto_anak');

        if ($request->hasFile('foto_anak')) {

            if (
                $anak->foto_anak &&
                Storage::disk('public')->exists($anak->foto_anak)
            ) {
                Storage::disk('public')->delete($anak->foto_anak);
            }

            $data['foto_anak'] = $request
                ->file('foto_anak')
                ->store('foto_anak', 'public');
        }

        $anak->update($data);

        return redirect()
            ->route('admin.anak_terpisah.index')
            ->with('success', 'Data berhasil diupdate');
    }

    // Hapus data
    public function destroy($id)
    {
        $anak = AnakTerpisah::findOrFail($id);

        if (
            $anak->foto_anak &&
            Storage::disk('public')->exists($anak->foto_anak)
        ) {
            Storage::disk('public')->delete($anak->foto_anak);
        }

        $anak->delete();

        return redirect()
            ->route('admin.anak_terpisah.index')
            ->with('success', 'Data berhasil dihapus');
    }
}