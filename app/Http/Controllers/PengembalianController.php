<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengembalian;
use App\Models\Pengambilan;
use App\Models\Barang;

class PengembalianController extends Controller
{
   public function index(Request $request)
{
    // 1. Mulai query dengan Eager Loading relasi terkait
    $query = Pengembalian::with([
        'pengambilan.barang',
        'petugas',
        'posko'
    ]);

    // 2. Logika Fitur Search Bar
    if ($request->filled('search')) {
        $search = $request->search;

        // Gunakan fungsi closure di mana bertujuan mengelompokkan kondisi OR (Logical Grouping)
        $query->where(function ($mainQuery) use ($search) {
            
            // Cari berdasarkan Keterangan Pengembalian
            $mainQuery->where('keterangan', 'like', "%$search%")
                      ->orWhere('status', 'like', "%$search%");

            // Cari berdasarkan data Pengambilan (Tujuan atau Nama Barang)
            $mainQuery->orWhereHas('pengambilan', function ($q) use ($search) {
                $q->where('tujuan', 'like', "%$search%")
                  ->orWhereHas('barang', function ($b) use ($search) {
                      $b->where('nama_barang', 'like', "%$search%");
                  });
            });

            // Cari berdasarkan Nama Petugas
            $mainQuery->orWhereHas('petugas', function ($q) use ($search) {
                $q->where('nama_petugas', 'like', "%$search%");
            });

            // Cari berdasarkan Nama Posko
            $mainQuery->orWhereHas('posko', function ($q) use ($search) {
                $q->where('nama_posko', 'like', "%$search%");
            });
        });
    }

    // 3. Filter berdasarkan Status Dropdown (jika ada)
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // 4. Ambil data terbaru (Sangat disarankan memakai paginate agar performa aplikasi ringan)
    $data = $query->latest()->paginate(10); 

    return view('management_barang.pengembalian.index', compact('data'));
}

public function create()
{
    $pengambilan = Pengambilan::with([
            'barang',
            'petugas',
            'posko',
            'bencana'
        ])
        ->where('status', 'Ditangani')
        ->get();

    return view(
        'management_barang.pengembalian.create',
        compact('pengambilan')
    );
}

public function store(Request $request)
{
    $request->validate([

        'pengambilan_id' => 'required|array',

        'jumlah_kembali' => 'required|array',

        'tanggal_pengembalian' => 'required|date',

        'status' =>
            'required|in:Ditangani,Selesai,Dibatalkan',

        'keterangan' =>
            'nullable|max:255',

    ]);

    $berhasil = false;

    foreach ($request->pengambilan_id as $index => $id) {

        $pengambilan = Pengambilan::with([
            'barang',
            'petugas',
            'posko'
        ])->find($id);

        if (!$pengambilan) {
            continue;
        }

        $jumlahKembali =
            $request->jumlah_kembali[$index] ?? 0;

        // skip jika kosong
        if ($jumlahKembali <= 0) {
            continue;
        }

        // jika dibatalkan
        if ($request->status == 'Dibatalkan') {

            $jumlahKembali = 0;
        }

        // validasi max
        if (
            $jumlahKembali >
            $pengambilan->jumlah_ambil
        ) {

            return back()
                ->withInput()
                ->withErrors([

                    'jumlah_kembali' =>
                        'Jumlah kembali melebihi jumlah ambil untuk barang '
                        . ($pengambilan->barang->nama_barang ?? '-')

                ]);
        }

        // INSERT
        Pengembalian::create([

            'pengambilan_id' =>
                $pengambilan->id,

            'petugas_id' =>
                $pengambilan->petugas_id,

            'posko_id' =>
                $pengambilan->posko_id,

            'tanggal_pengembalian' =>
                $request->tanggal_pengembalian,

            'jumlah_kembali' =>
                $jumlahKembali,

            'keterangan' =>
                $request->keterangan,

            'status' =>
                $request->status,

        ]);

        // UPDATE STOK
        if ($request->status == 'Selesai') {

            $barang = $pengambilan->barang;

            if ($barang) {

                $barang->stok += $jumlahKembali;

                $barang->save();
            }
        }

        $berhasil = true;
    }

    if (!$berhasil) {

        return back()
            ->withInput()
            ->withErrors([

                'pengambilan_id' =>
                    'Minimal isi 1 jumlah pengembalian'

            ]);
    }

    return redirect()
        ->route('admin.management_barang.pengembalian.index')
        ->with(
            'success',
            'Data pengembalian berhasil disimpan'
        );
}

public function getPengambilan($id)
{
    $pengambilan = Pengambilan::find($id);

    if (!$pengambilan) {
        return response()->json([]);
    }

    $data = Pengambilan::with([
            'barang',
            'petugas',
            'posko',
            'bencana'
        ])
        ->where('bencana_id', $pengambilan->bencana_id)
        ->where('tanggal_pengambilan', $pengambilan->tanggal_pengambilan)
        ->where('petugas_id', $pengambilan->petugas_id)
        ->where('posko_id', $pengambilan->posko_id)
        ->where('tujuan', $pengambilan->tujuan)
        ->get();

    return response()->json($data);
}

   public function edit($id)
{
    // Data utama pengembalian yang dipilih
    $data = Pengembalian::with([
        'pengambilan.bencana',
        'pengambilan.barang',
        'petugas',
        'posko'
    ])->findOrFail($id);

    // Ambil semua detail pengembalian dalam grup yang sama
    $detail = Pengembalian::with([
        'pengambilan.barang',
        'pengambilan.bencana',
        'petugas',
        'posko'
    ])
    ->whereHas('pengambilan', function ($q) use ($data) {
        $q->where('tujuan', $data->pengambilan->tujuan)
          ->where('tanggal_pengambilan', $data->pengambilan->tanggal_pengambilan)
          ->where('posko_id', $data->pengambilan->posko_id)
          ->where('bencana_id', $data->pengambilan->bencana_id);
    })
    ->get();

    // Data pengambilan untuk dropdown
    $pengambilan = Pengambilan::with([
        'bencana',
        'barang',
        'petugas',
        'posko'
    ])
    ->where('status', '!=', 'Dibatalkan')
    ->get()
    ->unique(function ($item) {
        return $item->tujuan .
               $item->tanggal_pengambilan .
               $item->posko_id .
               $item->bencana_id;
    });

    return view(
        'management_barang.pengembalian.edit',
        compact('data', 'detail', 'pengambilan')
    );
}

   public function update(Request $request, $id)
{
    $request->validate([
        'tanggal_pengembalian' => 'required|date',
        'status'               => 'required',
        'pengambilan_id'       => 'required|array',
        'pengembalian_id'      => 'required|array', // Pastikan ini divalidasi
        'jumlah_kembali'       => 'required|array',
    ]);

    foreach ($request->pengambilan_id as $index => $pengambilanId) {
        $pengembalianIdSpesifik = $request->pengembalian_id[$index] ?? null;

        // Cari berdasarkan ID spesifik dari tabel pengembalian agar tidak mengupdate data milik orang lain/transaksi lain
        if ($pengembalianIdSpesifik) {
            $kembali = \App\Models\Pengembalian::find($pengembalianIdSpesifik);
            if ($kembali) {
                $kembali->update([
                    'tanggal_pengembalian' => $request->tanggal_pengembalian,
                    'jumlah_kembali'       => $request->jumlah_kembali[$index] ?? 0,
                    'status'               => $request->status,
                    'keterangan'           => $request->keterangan,
                ]);
            }
        }
    }

    return redirect()->route('admin.management_barang.pengembalian.index')
                     ->with('success', 'Data pengembalian berhasil diperbarui.');
}    
public function show($id)
    {
        $data = Pengembalian::with([
            'pengambilan.barang',
            'pengambilan.bencana',
            'petugas',
            'posko'
        ])->findOrFail($id);

        return view(
            'management_barang.pengembalian.show',
            compact('data')
        );
    }
   public function destroy($id)
{
    // 1. Cari data pengembalian induk yang ingin dihapus
    $data = \App\Models\Pengembalian::findOrFail($id);

    // 2. Hapus semua data pengembalian yang bernaung di bawah transaksi/grup yang sama 
    // (Berdasarkan kesamaan tanggal, petugas, dan posko saat data itu dibuat)
    \App\Models\Pengembalian::where('tanggal_pengembalian', $data->tanggal_pengembalian)
        ->where('petugas_id', $data->petugas_id)
        ->where('posko_id', $data->posko_id)
        ->delete();

    return redirect()->route('admin.management_barang.pengembalian.index')
                     ->with('success', 'Data pengembalian berhasil dihapus.');
}
    
}