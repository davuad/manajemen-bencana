<?php

namespace App\Http\Controllers;

use App\Models\PaketBantuan;
use App\Models\Posko;
use Illuminate\Http\Request;

class PaketBantuanController extends Controller
{
    public function index(Request $request)
    {
        $query = PaketBantuan::with('posko');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_paket', 'like', '%' . $request->search . '%')
                    ->orWhere('id', $request->search);
            });
        }

        if ($request->posko) {
            $query->where('posko_id', $request->posko);
        }

        $paket_bantuan = $query->orderBy('id', 'asc')->paginate(5);

        $posko = Posko::all();

        return view('management_distribusi.paket_bantuan.index', compact('paket_bantuan', 'posko'));
    }

    public function create()
    {
        $posko = Posko::where('status', 'aktif')->get();

        return view('management_distribusi.paket_bantuan.create', compact('posko'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|max:50',
            'posko_id' => 'required|exists:posko,id',
            'keterangan' => 'nullable',
            'status' => 'required|in:aktif,non aktif',
        ]);

        PaketBantuan::create([
            'nama_paket' => $request->nama_paket,
            'posko_id' => $request->posko_id,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
        ]);

        $prefix = request()->segment(1);

        return redirect()->route($prefix . '.management_distribusi.paket_bantuan.index')
            ->with('success', 'Data paket bantuan berhasil ditambahkan');
    }

    public function edit(int $id)
    {
        $paket_bantuan = PaketBantuan::findOrFail($id);
        $posko = Posko::where('status', 'aktif')->get();

        return view('management_distribusi.paket_bantuan.edit', compact('paket_bantuan', 'posko'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'nama_paket' => 'required|max:50',
            'posko_id' => 'required|exists:posko,id',
            'keterangan' => 'nullable',
            'status' => 'required|in:aktif,non aktif',
        ]);

        $paket_bantuan = PaketBantuan::findOrFail($id);

        $paket_bantuan->update([
            'nama_paket' => $request->nama_paket,
            'posko_id' => $request->posko_id,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
        ]);

        $prefix = request()->segment(1);

        return redirect()->route($prefix . '.management_distribusi.paket_bantuan.index')
            ->with('success', 'Data paket bantuan berhasil diperbarui');
    }

    public function destroy(int $id)
    {
        $paket_bantuan = PaketBantuan::findOrFail($id);
        $paket_bantuan->delete();

        $prefix = request()->segment(1);

        return redirect()->route($prefix . '.management_distribusi.paket_bantuan.index')
            ->with('success', 'Data paket bantuan berhasil dihapus');
    }
}
