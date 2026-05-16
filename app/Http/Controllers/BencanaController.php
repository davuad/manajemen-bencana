<?php

namespace App\Http\Controllers;

use App\Models\Bencana;
use App\Models\KategoriBencana;
use App\Models\Desa;
use App\Models\PengaduanBencana;
use Illuminate\Http\Request;

class BencanaController extends Controller
{
    public function index(Request $request)
    {
        $query = Bencana::with(['kategori','desa','pengaduan']);

        // SEARCH kategori
        if ($request->search) {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('nama_kategori', 'like', '%' . $request->search . '%');
            });
        }

        // FILTER kategori
        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // FILTER kerusakan
        if ($request->tingkat_kerusakan) {
            $query->where('tingkat_kerusakan', $request->tingkat_kerusakan);
        }

        $bencana = $query->latest()->paginate(5);
        $kategori = KategoriBencana::all();

        return view('bencana.index', compact('bencana', 'kategori'));
    }

    public function create()
    {
        return view('bencana.create', [
            'kategori' => KategoriBencana::all(),
            'desa' => Desa::all(),
            'pengaduan' => PengaduanBencana::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bencana' => 'required',
            'kategori_id' => 'required|exists:kategori_bencana,id',
            'desa_id' => 'nullable|exists:desa,id',
            'pengaduan_id' => 'nullable|exists:pengaduan_bencana,id',
            'tanggal' => 'required|date',
            'status_bencana' => 'required|in:berlangsung,selesai',
            'tingkat_kerusakan' => 'required'
        ]);

        Bencana::create($request->all());

        return redirect()->route('bencana.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        return view('bencana.edit', [
            'bencana' => Bencana::findOrFail($id),
            'kategori' => KategoriBencana::all(),
            'desa' => Desa::all(),
            'pengaduan' => PengaduanBencana::all()
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_bencana' => 'required',
            'kategori_id' => 'required|exists:kategori_bencana,id',
            'desa_id' => 'nullable|exists:desa,id',
            'pengaduan_id' => 'nullable|exists:pengaduan_bencana,id',
            'tanggal' => 'required|date',
            'status_bencana' => 'required|in:berlangsung,selesai',
            'tingkat_kerusakan' => 'required'
        ]);

        $bencana = Bencana::findOrFail($id);
        $bencana->update($request->all());

        return redirect()->route('bencana.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        Bencana::findOrFail($id)->delete();

        return redirect()->route('bencana.index')
            ->with('success', 'Data berhasil dihapus');
    }
}