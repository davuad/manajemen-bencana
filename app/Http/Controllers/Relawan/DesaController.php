<?php

namespace App\Http\Controllers\Relawan;

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

    
}