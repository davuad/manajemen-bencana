<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DesaController extends Controller
{
    /**
     * Helper untuk redirect ke route sesuai role user yang login.
     * Contoh: redirectRoute('index') -> admin.desa.index / relawan.desa.index
     */
    private function redirectRoute($suffix)
{
    $role = Auth::user()->roles->first()->name ?? 'admin';
    return redirect()->route($role . '.desa.' . $suffix);
}

    public function index(Request $request)
    {
        $search = $request->search;
        $filterDesa = $request->desa;
        $filterKecamatan = $request->kecamatan;

        $query = Desa::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_desa', 'like', "%{$search}%")
                    ->orWhere('kecamatan', 'like', "%{$search}%")
                    ->orWhere('nama_kades', 'like', "%{$search}%");
            });
        }

        if (!empty($filterDesa)) {
            $query->where('nama_desa', $filterDesa);
        }

        if (!empty($filterKecamatan)) {
            $query->where('kecamatan', $filterKecamatan);
        }

        $desa = $query->orderBy('id', 'asc')
            ->paginate(6)
            ->withQueryString();

        $listDesa = Desa::select('nama_desa')
            ->distinct()
            ->orderBy('nama_desa')
            ->pluck('nama_desa');

        $listKecamatan = Desa::select('kecamatan')
            ->distinct()
            ->orderBy('kecamatan')
            ->pluck('kecamatan');

        return view('management_warga.desa.index', compact(
            'desa',
            'search',
            'filterDesa',
            'filterKecamatan',
            'listDesa',
            'listKecamatan'
        ));
    }

    public function create()
    {
        return view('management_warga.desa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_desa'     => 'required|string|max:100',
            'kecamatan'     => 'required|string|max:100',
            'nama_kades'    => 'required|string|max:100',
            'kontak_kades'  => 'required|string|max:20',
        ]);

        Desa::create([
            'nama_desa'     => $request->nama_desa,
            'kecamatan'     => $request->kecamatan,
            'nama_kades'    => $request->nama_kades,
            'kontak_kades'  => $request->kontak_kades,
        ]);

        return $this->redirectRoute('index')
            ->with('success', 'Data desa berhasil ditambahkan.');
    }

    public function show($id)
    {
        $desa = Desa::findOrFail($id);

        return view('management_warga.desa.detail', compact('desa'));
    }

    public function edit($id)
    {
        $desa = Desa::findOrFail($id);

        return view('management_warga.desa.edit', compact('desa'));
    }

    public function update(Request $request, $id)
    {
        $desa = Desa::findOrFail($id);

        $request->validate([
            'nama_desa'     => 'required|string|max:100',
            'kecamatan'     => 'required|string|max:100',
            'nama_kades'    => 'required|string|max:100',
            'kontak_kades'  => 'required|string|max:20',
        ]);

        $desa->update([
            'nama_desa'     => $request->nama_desa,
            'kecamatan'     => $request->kecamatan,
            'nama_kades'    => $request->nama_kades,
            'kontak_kades'  => $request->kontak_kades,
        ]);

        return $this->redirectRoute('index')
            ->with('success', 'Data desa berhasil diupdate.');
    }

    public function destroy($id)
    {
        $desa = Desa::find($id);

        if ($desa) {
            $desa->delete();
        }

        return $this->redirectRoute('index')
            ->with('success', 'Data desa berhasil dihapus.');
    }
}