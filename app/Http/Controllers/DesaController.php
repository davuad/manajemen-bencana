<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use Illuminate\Http\Request;

class DesaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $filterDesa = $request->desa;
        $filterKecamatan = $request->kecamatan;

        $query = Desa::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_desa', 'like', '%' . $search . '%')
                    ->orWhere('kecamatan', 'like', '%' . $search . '%')
                    ->orWhere('nama_kades', 'like', '%' . $search . '%');
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
            ->orderBy('nama_desa', 'asc')
            ->pluck('nama_desa');

        $listKecamatan = Desa::select('kecamatan')
            ->distinct()
            ->orderBy('kecamatan', 'asc')
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
            'nama_desa' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'nama_kades' => 'required|string|max:100',
            'kontak_kades' => 'required|string|max:20',
        ]);

        Desa::create([
            'nama_desa' => $request->nama_desa,
            'kecamatan' => $request->kecamatan,
            'nama_kades' => $request->nama_kades,
            'kontak_kades' => $request->kontak_kades,
        ]);

        return redirect()->route('admin.desa.index')->with('success', 'Data desa berhasil ditambahkan.');
    }

    public function detail($id)
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
            'nama_desa' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'nama_kades' => 'required|string|max:100',
            'kontak_kades' => 'required|string|max:20',
        ]);

        $desa->update([
            'nama_desa' => $request->nama_desa,
            'kecamatan' => $request->kecamatan,
            'nama_kades' => $request->nama_kades,
            'kontak_kades' => $request->kontak_kades,
        ]);

        return redirect()->route('admin.desa.index')->with('success', 'Data desa berhasil diupdate.');
    }

    public function delete($id)
    {
        // Gunakan find(), jangan findOrFail() supaya tidak memicu 404 otomatis
        $desa = Desa::find($id);

        if ($desa) {
            $desa->delete();
            return redirect()->route('admin.desa.index')->with('success', 'Data desa berhasil dihapus.');
        }

        // Jika data sudah tidak ada (terhapus), langsung balikkan ke index dengan aman
        return redirect()->route('admin.desa.index')->with('success', 'Data desa sudah berhasil dihapus.');
    }
}
