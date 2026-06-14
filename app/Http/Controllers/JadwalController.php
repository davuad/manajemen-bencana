<?php

namespace App\Http\Controllers;

use App\Models\Bencana;
use App\Models\Pegawai;
use App\Models\JadwalLayanan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil daftar tahun unik dari database untuk pilihan filter
        $tahunTersedia = JadwalLayanan::select('tanggal_layanan')
            ->pluck('tanggal_layanan')
            ->map(fn($date) => \Carbon\Carbon::parse($date)->year)
            ->unique()
            ->sortDesc()
            ->values();

        // 2. Inisialisasi query dengan Eager Loading
        $query = JadwalLayanan::with(['pegawai', 'bencana.desa', 'bencana.kategori']);

        // 3. Terapkan Filter
        if ($request->filled('bencana_id')) {
            $query->where('bencana_id', $request->bencana_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_layanan', $request->tahun);
        }

        // 4. Eksekusi data
        $jadwals = $query->latest()->get();
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

        // Pastikan filter di PDF sama dengan yang ada di index
        if ($request->filled('bencana_id')) {
            $query->where('bencana_id', $request->bencana_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_layanan', $request->tahun);
        }

        $jadwals = $query->get();

        $pdf = Pdf::loadView('jadwal.cetak-pdf', compact('jadwals'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('laporan-jadwal.pdf');
    }
}
