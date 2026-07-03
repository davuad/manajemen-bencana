<?php

namespace App\Http\Controllers;

use App\Models\DapurUmum;
use App\Models\KebutuhanHarian;
use Illuminate\Http\Request;

class KebutuhanHarianController extends Controller
{
    public function index(Request $request, $role, $dapur)
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

    public function create($role, $dapur)
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
    public function store(Request $request, $role, $dapur)
    {
        $dapur = DapurUmum::findOrFail($dapur);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jumlah_warga' => 'required|integer|min:1',
            'porsi_per_orang' => 'required|integer|min:1',
            'realisasi_porsi' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string|max:1000',
        ]);

        // AUTO HITUNG TOTAL
        $totalPorsi = $request->jumlah_warga * $request->porsi_per_orang;

        KebutuhanHarian::create([
            'dapur_umum_id' => $dapur->id,
            'tanggal' => $request->tanggal,
            'jumlah_warga' => $request->jumlah_warga,
            'porsi_per_orang' => $request->porsi_per_orang,
            'total_porsi' => $totalPorsi,
            'realisasi_porsi' => $request->realisasi_porsi,
            'catatan' => $request->catatan,
        ]);

        return redirect()
            ->route(
                'management_posko.kebutuhan_harian.index',
                [
                    'role'  => $role,
                    'dapur' => $dapur->id
                ]
            )
            ->with(
                'success',
                'Data kebutuhan harian berhasil ditambahkan'
            );
    }

    /**
     * FORM EDIT
     */
    public function edit($role, $dapur, $id)
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
    public function update(Request $request, $role, $dapur, $id)
    {
        $kebutuhan = KebutuhanHarian::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jumlah_warga' => 'required|integer|min:1',
            'porsi_per_orang' => 'required|integer|min:1',
            'realisasi_porsi' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string|max:1000',
        ]);

        // AUTO HITUNG TOTAL
        $totalPorsi = $request->jumlah_warga * $request->porsi_per_orang;

        $kebutuhan->update([
            'tanggal' => $request->tanggal,
            'jumlah_warga' => $request->jumlah_warga,
            'porsi_per_orang' => $request->porsi_per_orang,
            'total_porsi' => $totalPorsi,
            'realisasi_porsi' => $request->realisasi_porsi,
            'catatan' => $request->catatan,
        ]);

        return redirect()
            ->route(
                'management_posko.kebutuhan_harian.index',
                [
                    $kebutuhan->dapur_umum_id,
                    'role' => $role
                ]
            )
            ->with(
                'success',
                'Data kebutuhan harian berhasil diupdate'
            );
    }

    /**
     * DELETE
     */
    public function destroy($role, $dapur, $id)
    {
        $kebutuhan = KebutuhanHarian::findOrFail($id);

        $dapurId = $kebutuhan->dapur_umum_id;

        $kebutuhan->delete();

        return redirect()
            ->route(
                'management_posko.kebutuhan_harian.index', [
                $dapurId,
                'role' => $role]
            )
            ->with(
                'success',
                'Data kebutuhan harian berhasil dihapus'
            );
    }
}
