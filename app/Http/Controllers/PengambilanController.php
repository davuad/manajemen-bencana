<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengambilan;
use App\Models\Petugas;
use App\Models\Bencana;
use App\Models\Posko;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PengambilanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengambilan::with([
            'barang',
            'petugas',
            'bencana',
            'posko'
        ]);

        // search
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tujuan', 'like', "%{$search}%")
                ->orWhereHas('petugas', function ($p) use ($search) {
                    $p->where('nama_petugas', 'like', "%{$search}%");
                })
                ->orWhereHas('posko', function ($p) use ($search) {
                    $p->where('nama_posko', 'like', "%{$search}%");
                });
            });
        }

        $data = $query->latest('id')
            ->get()
            // grouping berdasarkan kesamaan transaksi induk
            ->groupBy(function ($item) {
                return $item->bencana_id .
                    '-' . $item->petugas_id .
                    '-' . $item->tanggal_pengambilan .
                    '-' . $item->posko_id .
                    '-' . $item->tujuan;
            })
            ->map(function ($group) {
                return $group->first();
            });

        return view('management_barang.pengambilan.index', compact('data'));
    }

    public function create()
    {
        $barang  = Barang::all();
        $bencana = Bencana::all();
        $petugas = Petugas::whereNull('deleted_at')->get();
        $posko   = Posko::all();

        return view('management_barang.pengambilan.create', compact('barang', 'bencana', 'petugas', 'posko'));
    }

public function store(Request $request)
    {
        $request->validate([
            'bencana_id' => 'required|exists:bencana,id',
            'petugas_id' => 'required|exists:petugas,id',
            'posko_id' => 'required|exists:posko,id',
            'tanggal_pengambilan' => 'required|date',
            'tujuan' => 'required|max:100',
            'barang_id.*' => 'nullable|exists:barang,id_barang', 
            'jumlah_ambil.*' => 'nullable|integer|min:1',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Tambahkan validasi gambar
        ]);

        $adaBarang = false;

        try {
            DB::beginTransaction();

            // PROSES UPLOAD GAMBAR DOKUMEN
            $pathGambar = null;
            if ($request->hasFile('gambar')) {
                $pathGambar = $request->file('gambar')->store('pengambilan', 'public');
            }

            foreach ($request->barang_id as $index => $barangId) {
                if (empty($barangId) || empty($request->jumlah_ambil[$index])) {
                    continue;
                }

                $adaBarang = true;
                $jumlah = $request->jumlah_ambil[$index];
                $barang = Barang::findOrFail($barangId);

                if ($jumlah > $barang->stok) {
                    return back()->withErrors([
                        'jumlah_ambil' => 'Jumlah melebihi stok untuk ' . $barang->nama_barang
                    ])->withInput();
                }

                // Simpan data pengambilan beserta path gambarnya
                Pengambilan::create([
                    'barang_id' => $barangId,
                    'bencana_id' => $request->bencana_id,
                    'petugas_id' => $request->petugas_id,
                    'posko_id' => $request->posko_id,
                    'tanggal_pengambilan' => $request->tanggal_pengambilan,
                    'jumlah_ambil' => $jumlah,
                    'tujuan' => $request->tujuan,
                    'gambar' => $pathGambar,
                    'status' => 'Ditangani'
                ]);

                $barang->stok -= $jumlah;
                $barang->save();
            }

            if (!$adaBarang) {
                return back()->withErrors([
                    'barang_id' => 'Minimal pilih 1 barang'
                ])->withInput();
            }

            DB::commit();
            return redirect()->route('admin.management_barang.pengambilan.index')->with('success', 'Data pengambilan berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['barang_id' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $data = Pengambilan::findOrFail($id);

        $barangPengambilan = Pengambilan::where('tanggal_pengambilan', $data->tanggal_pengambilan)
            ->where('petugas_id', $data->petugas_id)
            ->where('tujuan', $data->tujuan)
            ->where('posko_id', $data->posko_id)
            ->where('bencana_id', $data->bencana_id)
            ->get();

        $barang = Barang::all();
        $bencana = Bencana::all();
        $petugas = Petugas::whereNull('deleted_at')->get();
        $posko = Posko::all();

        return view('management_barang.pengambilan.edit', compact('data', 'barang', 'barangPengambilan', 'bencana', 'petugas', 'posko'));
    }

   public function update(Request $request, $id)
    {
        $request->validate([
            'bencana_id' => 'required|exists:bencana,id',
            'petugas_id' => 'required|exists:petugas,id',
            'posko_id' => 'required|exists:posko,id',
            'tanggal_pengambilan' => 'required|date',
            'tujuan' => 'required|max:100',
            'status' => 'required|in:Ditangani,Selesai,Dibatalkan',
            'barang_id.*' => 'nullable|exists:barang,id_barang',
            'jumlah_ambil.*' => 'nullable|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Tambahkan validasi gambar
        ]);

        $dataLama = Pengambilan::findOrFail($id);
        $statusDibatalkan = $request->status == 'Dibatalkan';

        $validItems = [];
        if ($request->has('barang_id')) {
            foreach ($request->barang_id as $index => $bId) {
                $jumlah = isset($request->jumlah_ambil[$index]) ? (int)$request->jumlah_ambil[$index] : 0;
                
                if (!empty($bId)) {
                    if (!$statusDibatalkan && $jumlah <= 0) {
                        continue; 
                    }
                    $validItems[] = [
                        'barang_id' => $bId,
                        'jumlah_ambil' => $jumlah
                    ];
                }
            }
        }

        if (empty($validItems) && !$statusDibatalkan) {
            return redirect()->route('admin.management_barang.pengambilan.edit', $id)
                ->withErrors(['barang_id' => 'Minimal pilih 1 barang dengan jumlah yang valid.'])
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $groupData = Pengambilan::where('tanggal_pengambilan', $dataLama->tanggal_pengambilan)
                ->where('petugas_id', $dataLama->petugas_id)
                ->where('tujuan', $dataLama->tujuan)
                ->where('posko_id', $dataLama->posko_id)
                ->get();

            // Pertahankan gambar lama jika tidak upload gambar baru
            $pathGambar = $dataLama->gambar; 
            if ($request->hasFile('gambar')) {
                // Hapus gambar fisik lama jika ada
                if ($dataLama->gambar && \Illuminate\Support\Facades\Storage::disk('public')->exists($dataLama->gambar)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($dataLama->gambar);
                }
                $pathGambar = $request->file('gambar')->store('pengambilan', 'public');
            }

            foreach ($groupData as $old) {
                if ($old->status != 'Dibatalkan') {
                    $barangOld = Barang::find($old->barang_id);
                    if ($barangOld) {
                        $barangOld->stok += $old->jumlah_ambil;
                        $barangOld->save();
                    }
                }
            }

            if (!$statusDibatalkan) {
                foreach ($validItems as $item) {
                    $barang = Barang::find($item['barang_id']);
                    if (!$barang) throw new \Exception('Data barang tidak ditemukan.');
                    if ($item['jumlah_ambil'] > $barang->stok) {
                        throw new \Exception('Jumlah melebihi stok tersedia untuk ' . $barang->nama_barang);
                    }
                }
            }

            Pengambilan::where('tanggal_pengambilan', $dataLama->tanggal_pengambilan)
                ->where('petugas_id', $dataLama->petugas_id)
                ->where('tujuan', $dataLama->tujuan)
                ->where('posko_id', $dataLama->posko_id)
                ->where('bencana_id', $dataLama->bencana_id)
                ->delete();

            if ($statusDibatalkan) {
                Pengambilan::create([
                    'barang_id' => $dataLama->barang_id ?? $request->barang_id[0],
                    'bencana_id' => $request->bencana_id,
                    'petugas_id' => $request->petugas_id,
                    'posko_id' => $request->posko_id,
                    'tanggal_pengambilan' => $request->tanggal_pengambilan,
                    'jumlah_ambil' => 0,
                    'tujuan' => $request->tujuan,
                    'gambar' => $pathGambar,
                    'status' => 'Dibatalkan',
                ]);
            } else {
                foreach ($validItems as $item) {
                    $barang = Barang::findOrFail($item['barang_id']);

                    Pengambilan::create([
                        'barang_id' => $item['barang_id'],
                        'bencana_id' => $request->bencana_id,
                        'petugas_id' => $request->petugas_id,
                        'posko_id' => $request->posko_id,
                        'tanggal_pengambilan' => $request->tanggal_pengambilan,
                        'jumlah_ambil' => $item['jumlah_ambil'],
                        'tujuan' => $request->tujuan,
                        'gambar' => $pathGambar, // Masukkan gambar yang diperbarui ke rombongan barang baru
                        'status' => $request->status,
                    ]);

                    $barang->stok -= $item['jumlah_ambil'];
                    $barang->save();
                }
            }

            DB::commit();
            return redirect()->route('admin.management_barang.pengambilan.index')->with('success', 'Data pengambilan berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.management_barang.pengambilan.edit', $id)->withErrors(['jumlah_ambil' => $e->getMessage()])->withInput();
        }
    }
    public function batal($id)
    {
        $data = Pengambilan::findOrFail($id);

        $groupData = Pengambilan::where('tanggal_pengambilan', $data->tanggal_pengambilan)
            ->where('petugas_id', $data->petugas_id)
            ->where('tujuan', $data->tujuan)
            ->where('posko_id', $data->posko_id)
            ->get();

        foreach ($groupData as $item) {
            if ($item->status != 'Dibatalkan') {
                $barang = Barang::find($item->barang_id);
                if ($barang) {
                    $barang->stok += $item->jumlah_ambil;
                    $barang->save();
                }
            }
            $item->update([
                'status' => 'Dibatalkan',
                'jumlah_ambil' => 0
            ]);
        }

        return redirect()->route('admin.management_barang.pengambilan.index')->with('success', 'Pengambilan berhasil dibatalkan');
    }

    public function show($id)
    {
        $data = Pengambilan::with(['barang', 'bencana', 'petugas', 'posko'])->findOrFail($id);

        // Ambil barang-barang lain yang berada dalam rombongan grup transaksi yang sama
        $barangPengambilan = Pengambilan::with('barang')
            ->where('tanggal_pengambilan', $data->tanggal_pengambilan)
            ->where('petugas_id', $data->petugas_id)
            ->where('tujuan', $data->tujuan)
            ->where('posko_id', $data->posko_id)
            ->where('bencana_id', $data->bencana_id)
            ->get();

        return view('management_barang.pengambilan.show', compact('data', 'barangPengambilan'));
    }

   public function cetak($id)
{
    $data = Pengambilan::findOrFail($id);
    
    // Ambil data detail barang
    $barangPengambilan = Pengambilan::where('tanggal_pengambilan', $data->tanggal_pengambilan)
        ->where('petugas_id', $data->petugas_id)
        ->where('tujuan', $data->tujuan)
        ->get();

    // Tambahkan ->setOption(...) di baris ini
   $pdf = Pdf::loadView(
    'management_barang.pengambilan.pdf',
    compact('data', 'barangPengambilan')
);
    return $pdf->stream('laporan-pengambilan.pdf');
    }
}