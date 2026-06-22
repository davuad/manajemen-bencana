<?php

namespace App\Http\Controllers;

use App\Models\Bencana;
use App\Models\Pegawai;
use App\Models\JadwalLayanan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil tahun unik untuk pilihan filter tahun
        $tahunTersedia = Bencana::select(DB::raw('YEAR(tanggal) as tahun'))
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->filter();

        // 2. Query dasar dengan Eager Loading agar tidak berat
        $query = JadwalLayanan::with(['pegawai', 'bencana.desa', 'bencana.kategori']);

        // 3. Filter (Bencana, Status, dan Tahun)
        if ($request->filled('bencana_id')) {
            $query->where('bencana_id', $request->bencana_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Rentang Tahun (Admin/Kabid)
        if ($request->filled('tahun_mulai')) {
            $query->whereHas('bencana', fn($q) => $q->whereYear('tanggal', '>=', $request->tahun_mulai));
        }
        if ($request->filled('tahun_selesai')) {
            $query->whereHas('bencana', fn($q) => $q->whereYear('tanggal', '<=', $request->tahun_selesai));
        }
        
        // Filter Tahun Tunggal (Relawan dll)
        if ($request->filled('tahun')) {
            $query->whereHas('bencana', fn($q) => $q->whereYear('tanggal', $request->tahun));
        }

        // 4. PAGINATION: 10 data per halaman + withQueryString agar filter tidak hilang saat pindah halaman
        $jadwals = $query->orderBy('tanggal_layanan', 'desc')
                         ->orderBy('jam_mulai', 'desc')
                         ->paginate(4)
                         ->withQueryString(); 
        
        $bencanas = Bencana::all();

        return view('jadwal.index', compact('jadwals', 'bencanas', 'tahunTersedia'));
    }

    public function create()
    {
        $bencanas = Bencana::with('kategori')->get();
        $pegawais = Pegawai::all();
        return view('jadwal.create', compact('bencanas', 'pegawais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bencana_id' => 'required|exists:bencana,id',
            'pegawai_id' => 'required|exists:pegawai,id_pegawai',
            'tanggal_layanan' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'jenis_layanan' => 'required|max:100',
            'sarana' => 'required|max:50',
            'petugas_lapangan' => 'required|max:100',
            'lokasi_layanan' => 'required|max:150',
            'status' => 'required|in:dijadwalkan,selesai',
        ]);

        JadwalLayanan::create($request->all());
        return redirect()->route('admin.jadwal.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(string $id)
    {
        $jadwal = JadwalLayanan::findOrFail($id);
        $bencanas = Bencana::with('kategori')->get();
        $pegawais = Pegawai::all();
        return view('jadwal.edit', compact('jadwal', 'bencanas', 'pegawais'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'bencana_id' => 'required|exists:bencana,id',
            'pegawai_id' => 'required|exists:pegawai,id_pegawai',
            'tanggal_layanan' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'jenis_layanan' => 'required|max:100',
            'sarana' => 'required|max:50',
            'petugas_lapangan' => 'required|max:100',
            'lokasi_layanan' => 'required|max:150',
        ]);

        $jadwal = JadwalLayanan::findOrFail($id);
        $jadwal->update($request->all());
        return redirect()->route('admin.jadwal.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy(string $id)
    {
        $jadwal = JadwalLayanan::findOrFail($id);
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Data berhasil dihapus');
    }

    public function cetak_pdf(Request $request)
    {
        $query = JadwalLayanan::with(['bencana', 'pegawai']);

        if ($request->filled('bencana_id')) $query->where('bencana_id', $request->bencana_id);
        if ($request->filled('status')) $query->where('status', $request->status);
        
        if ($request->filled('tahun_mulai')) $query->whereHas('bencana', fn($q) => $q->whereYear('tanggal', '>=', $request->tahun_mulai));
        if ($request->filled('tahun_selesai')) $query->whereHas('bencana', fn($q) => $q->whereYear('tanggal', '<=', $request->tahun_selesai));
        if ($request->filled('tahun')) $query->whereHas('bencana', fn($q) => $q->whereYear('tanggal', $request->tahun));

        $jadwals = $query->orderBy('tanggal_layanan', 'desc')->get();

        $pdf = Pdf::loadView('jadwal.cetak-pdf', compact('jadwals'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('laporan-jadwal.pdf');
    }
}