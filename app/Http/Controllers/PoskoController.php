<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\PengaduanBencana;
use App\Models\Posko;
use Illuminate\Http\Request;

class PoskoController extends Controller
{
    public function index()
    {
        $posko = Posko::with(['desa', 'pengaduan'])->get();

        return view('management_posko.posko.index', compact('posko'));
    }

    public function create()
    {
        $desa = Desa::all();
        $pengaduan = PengaduanBencana::where('status_pengaduan', '!=', 'SELESAI')->get();

        return view('management_posko.posko.create', compact('desa', 'pengaduan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_posko' => 'required|max:100',
            'tanggal_dibuat' => 'required|date',
            'id_desa' => 'required',
            'id_pengaduan' => 'required',
            'lokasi' => 'required'
        ]);

        Posko::create([
            'nama_posko' => $request->nama_posko,
            'tanggal_dibuat' => $request->tanggal_dibuat,
            'id_desa' => $request->id_desa,
            'id_pengaduan' => $request->id_pengaduan,
            'lokasi' => $request->lokasi
        ]);

        return redirect()->route('management_posko.posko.index');
    }
}
