<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\AnakTerpisah;
use App\Models\Bencana;
use Illuminate\Support\Facades\Storage;

class AnakTerpisahController extends Controller
{
    // Tampilkan semua data anak terpisah
    public function index(Request $request)
    {
        $query = AnakTerpisah::with('bencana')
            ->orderByDesc('id');
            
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_anak', 'like', '%' . $search . '%')
                ->orWhere('nama_bapak', 'like', '%' . $search . '%')
                ->orWhere('nama_ibu', 'like', '%' . $search . '%')
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

        return view('management_korban.anak_terpisah.index', compact('data'));
    }

    // Form tambah data
    public function create()
    {
        $bencana = Bencana::orderBy('nama_bencana')->get();
        
        return view('management_korban.anak_terpisah.create', compact('bencana'));
    }

    // Simpan data
    public function store(Request $request)
    {
        $request->validate([
            'bencana_id' => 'required|exists:bencana,id',
            'nama_anak' => 'required',
            'nama_bapak' => 'nullable|string',
            'nama_ibu' => 'nullable|string',
            'jenis_kelamin' => 'required|in:L,P',
            'umur' => 'nullable|integer',
            'tanggal_lahir' => 'nullable|date',
            'alamat_asal' => 'nullable|string',
            'lokasi_ditemukan' => 'required|string',
            'tanggal_ditemukan' => 'required|date',
            'kontak_keluarga' => 'nullable|string',
            'status_anak' => 'required|in:belum_dijemput,sudah_dijemput,dalam_proses',
            'foto_anak' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $foto = $request->file('foto_anak')->store('foto_anak', 'public');

        AnakTerpisah::create([
            'bencana_id' => $request->bencana_id,
            'nama_anak' => $request->nama_anak,
            'nama_bapak' => $request->nama_bapak,
            'nama_ibu'   => $request->nama_ibu,
            'jenis_kelamin' => $request->jenis_kelamin,
            'umur' => $request->umur,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat_asal' => $request->alamat_asal,
            'lokasi_ditemukan' => $request->lokasi_ditemukan,
            'tanggal_ditemukan' => $request->tanggal_ditemukan,
            'kontak_keluarga' => $request->kontak_keluarga,
            'status_anak' => $request->status_anak,
            'foto_anak' => $foto,
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $route = $user->hasRole('petugas')
            ? 'petugas.anak_terpisah.index'
            : 'admin.anak_terpisah.index';

        return redirect()
            ->route($route)
            ->with('success', 'Data berhasil disimpan');
    }

    // Detail data
    public function show($id)
{
    $anak = AnakTerpisah::with('bencana')->findOrFail($id);

    return view('management_korban.anak_terpisah.show', compact('anak'));
}

    // Form edit
    public function edit($id)
    {
        $anak = AnakTerpisah::findOrFail($id);

        $bencana = Bencana::orderBy('nama_bencana')->get();

        return view('management_korban.anak_terpisah.edit', compact('anak', 'bencana'));
    }

    // Update data
    public function update(Request $request, $id)
    {
        $anak = AnakTerpisah::findOrFail($id);

        $request->validate([
            'bencana_id' => 'required|exists:bencana,id',
            'nama_anak' => 'required',
            'nama_bapak' => 'nullable|string',
            'nama_ibu' => 'nullable|string',
            'jenis_kelamin' => 'required|in:L,P',
            'umur' => 'nullable|integer',
            'tanggal_lahir' => 'nullable|date',
            'alamat_asal' => 'nullable|string',
            'lokasi_ditemukan' => 'required|string',
            'tanggal_ditemukan' => 'required|date',
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

        /** @var \App\Models\User $user */
        $user = Auth::user();

            if ($user->hasRole('petugas')) {
    $route = 'petugas.anak_terpisah.index';
} elseif ($user->hasRole('relawan')) {
    $route = 'relawan.anak_terpisah.index';
} else {
    $route = 'admin.anak_terpisah.index';
}

        return redirect()
            ->route($route)
            ->with('success', 'Data berhasil diupdate');
    }

    // Hapus data
    public function destroy($id)
    {
        $anak = AnakTerpisah::findOrFail($id);

        // Hapus data penjemputan jika ada
        if ($anak->penjemputan) {

            if ($anak->penjemputan->bukti_dokumen) {
                Storage::disk('public')->delete($anak->penjemputan->bukti_dokumen);
            }

            if ($anak->penjemputan->berita_acara) {
                Storage::disk('public')->delete($anak->penjemputan->berita_acara);
            }

            $anak->penjemputan()->delete();
        }

        // Hapus foto anak
        if ($anak->foto_anak &&
            Storage::disk('public')->exists($anak->foto_anak)) {

            Storage::disk('public')->delete($anak->foto_anak);
        }

        // Hapus anak
        $anak->delete();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $route = $user->hasRole('petugas')
            ? 'petugas.anak_terpisah.index'
            : 'admin.anak_terpisah.index';

        return redirect()
            ->route($route)
            ->with('success', 'Data berhasil dihapus');
    }
}