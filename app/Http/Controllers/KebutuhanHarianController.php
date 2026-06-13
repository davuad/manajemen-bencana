<?php

namespace App\Http\Controllers;

use App\Models\DapurUmum;
use App\Models\KebutuhanHarian;
use Illuminate\Http\Request;

class KebutuhanHarianController extends Controller
{
    /**
     * INDEX
     * Menampilkan kebutuhan berdasarkan dapur umum tertentu
     */
    public function index(Request $request, $dapur)
    {
        $dapur = DapurUmum::findOrFail($dapur);

        $query = KebutuhanHarian::with('dapur_umum')
            ->where('dapur_umum_id', $dapur->id);

        // SEARCH
        if ($request->search) {

            $query->where(function ($q) use ($request) {

                $q->where('tanggal', 'like', '%' . $request->search . '%');
            });
        }

        $kebutuhan = $query
            ->orderBy('tanggal', 'desc')
            ->paginate(5);

        return view(
            'management_posko.kebutuhan_harian.index',
            compact('kebutuhan', 'dapur')
        );
    }

    /**
     * FORM CREATE
     */
    public function create($dapur)
    {
        $dapur = DapurUmum::findOrFail($dapur);

        return view(
            'management_posko.kebutuhan_harian.create',
            compact('dapur')
        );
    }

    /**
     * STORE
     */
    public function store(Request $request, $dapur)
    {
        $dapur = DapurUmum::findOrFail($dapur);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jumlah_warga' => 'required|integer|min:1',
            'porsi_per_orang' => 'required|integer|min:1',
        ]);

        // AUTO HITUNG TOTAL
        $totalPorsi = $request->jumlah_warga * $request->porsi_per_orang;

        KebutuhanHarian::create([
            'dapur_umum_id' => $dapur->id,
            'tanggal' => $request->tanggal,
            'jumlah_warga' => $request->jumlah_warga,
            'porsi_per_orang' => $request->porsi_per_orang,
            'total_porsi' => $totalPorsi,
        ]);

        return redirect()
            ->route(
                'admin.management_posko.kebutuhan_harian.index',
                $dapur->id
            )
            ->with(
                'success',
                'Data kebutuhan harian berhasil ditambahkan'
            );
    }

    /**
     * FORM EDIT
     */
    public function edit($id)
    {
        $kebutuhan = KebutuhanHarian::with('dapur_umum')
            ->findOrFail($id);
        $dapur = DapurUmum::all();

        return view(
            'management_posko.kebutuhan_harian.edit',
            compact('kebutuhan', 'dapur')
        );
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $kebutuhan = KebutuhanHarian::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jumlah_warga' => 'required|integer|min:1',
            'porsi_per_orang' => 'required|integer|min:1',
        ]);

        // AUTO HITUNG TOTAL
        $totalPorsi = $request->jumlah_warga * $request->porsi_per_orang;

        $kebutuhan->update([
            'tanggal' => $request->tanggal,
            'jumlah_warga' => $request->jumlah_warga,
            'porsi_per_orang' => $request->porsi_per_orang,
            'total_porsi' => $totalPorsi,
        ]);

        return redirect()
            ->route(
                'admin.management_posko.kebutuhan_harian.index',
                $kebutuhan->dapur_umum_id
            )
            ->with(
                'success',
                'Data kebutuhan harian berhasil diupdate'
            );
    }

    /**
     * DELETE
     */
    public function destroy($id)
    {
        $kebutuhan = KebutuhanHarian::findOrFail($id);

        $dapurId = $kebutuhan->dapur_umum_id;

        $kebutuhan->delete();

        return redirect()
            ->route(
                'admin.management_posko.kebutuhan_harian.index',
                $dapurId
            )
            ->with(
                'success',
                'Data kebutuhan harian berhasil dihapus'
            );
    }
}
