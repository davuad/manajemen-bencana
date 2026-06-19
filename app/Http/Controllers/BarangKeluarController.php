<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Gudang;
use App\Models\PengajuanBarang;
use App\Models\Pegawai;
use App\Models\Barang;
use App\Models\DetailBarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BarangKeluarController extends Controller
{

public function index(Request $request)
{
    $query = BarangKeluar::with([
        'gudang', 
        'petugasGudang', 
        'pengajuanBarang.bencana.desa', 
        'pengajuanBarang.bencana.kategoriBencana'
    ]);

    if ($request->search) {
        $query->where(function($q) use ($request) {
            $q->whereHas('gudang', fn($q2) => $q2->where('nama_gudang', 'like', '%' . $request->search . '%'))
              ->orWhereHas('petugasGudang', fn($q2) => $q2->where('nama_pegawai', 'like', '%' . $request->search . '%'));
        });
    }

    if ($request->nama_bencana) {
        $query->whereHas('pengajuanBarang.bencana', function($q) use ($request) {
            $q->whereHas('kategoriBencana', fn($q2) => $q2->where('nama_kategori', 'like', '%' . $request->nama_bencana . '%'))
              ->orWhereHas('desa', fn($q2) => $q2->where('nama_desa', 'like', '%' . $request->nama_bencana . '%'));
        });
    }

    if ($request->tahun) $query->whereYear('tgl_keluar', $request->tahun);
    if ($request->bulan) $query->whereMonth('tgl_keluar', $request->bulan);
    if ($request->status) $query->where('status_proses', $request->status);
    if ($request->gudang_id) $query->where('gudang_id', $request->gudang_id);

    if ($request->export == 'print') {
        $data = $query->get();
        return view('distribusi_bantuan.barang_keluar.export', compact('data'));
    }

    $data = $query->latest()->paginate(10)->withQueryString();
    $all_gudang = \App\Models\Gudang::all();

    return view('distribusi_bantuan.barang_keluar.index', compact('data', 'all_gudang'));
}



public function create()
{
    $gudang = Gudang::all();
    $pegawai = Pegawai::all();
   
    $pengajuan = PengajuanBarang::with(['bencana.desa', 'bencana.kategoriBencana'])
                ->where('status_pengajuan', 'disetujui')
                ->latest()
                ->get();

    return view('distribusi_bantuan.barang_keluar.create', compact('gudang', 'pengajuan', 'pegawai'));
}

public function getDetailPengajuan($id)
{
    $data = PengajuanBarang::with([
        'detailPengajuan.barang', 
        'pegawai', 
        'bencana.desa', 
        'bencana.kategoriBencana'
    ])->findOrFail($id);
    
    return response()->json($data);
}


    public function store(Request $request)
    {
        $request->validate([
            'gudang_id' => 'required',
            'pengajuan_barang_id' => 'required',
            'petugas_gudang_id' => 'required',
            'tgl_keluar' => 'required|date',
            'status_proses' => 'required|in:diproses,dikirim,selesai,dibatalkan',
            'barang_id' => 'required|array',
            'jumlah' => 'required|array', // Ini jumlah permintaan asli
            'jumlah_keluar' => 'required|array', // Ini jumlah realisasi gudang
        ]);

        try {
            DB::transaction(function () use ($request) {
                $bk = BarangKeluar::create([
                    'gudang_id' => $request->gudang_id,
                    'pengajuan_barang_id' => $request->pengajuan_barang_id,
                    'petugas_gudang_id' => $request->petugas_gudang_id,
                    'tgl_keluar' => $request->tgl_keluar,
                    'status_proses' => $request->status_proses,
                    'catatan' => $request->catatan,
                    'updated_by' => Auth::id() ?? 1,
                ]);

                foreach ($request->barang_id as $index => $id_barang) {
                    DetailBarangKeluar::create([
                        'barang_keluar_id' => $bk->id,
                        'barang_id' => $id_barang,
                        'jumlah' => $request->jumlah[$index], // Angka permintaan
                        'jumlah_keluar' => $request->jumlah_keluar[$index], // Angka realisasi
                    ]);
                }
            });

            return redirect()->route('distribusi_bantuan.barang_keluar.index')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Simpan: ' . $e->getMessage());
        }
    }



public function show($id)
{
    $data = BarangKeluar::with([
        'detailBarangKeluar.barang', 
        'gudang', 
        'petugasGudang', 
        'pengajuanBarang.pegawai',
        'pengajuanBarang.bencana.pengaduan',
        'pengajuanBarang.bencana.kategoriBencana', 
        'pengajuanBarang.bencana.desa'
    ])->findOrFail($id);

    $gudangs = [];
    $pegawais = [];

    if (!in_array($data->status_proses, ['selesai', 'dibatalkan'])) {
        $gudangs = \App\Models\Gudang::all();
        $pegawais = \App\Models\Pegawai::all();
    }

    return view('distribusi_bantuan.barang_keluar.show', compact('data', 'gudangs', 'pegawais'));
}

public function update(Request $request, $id)
{
    $bk = BarangKeluar::findOrFail($id);

    if (in_array($bk->status_proses, ['selesai', 'dibatalkan'])) {
        return redirect()->back()->with('error', 'Gagal! Data sudah bersifat final.');
    }

    $request->validate([
        'status_proses' => 'required|in:diproses,dikirim,selesai,dibatalkan',
        'gudang_id' => 'required|exists:gudang,id',
        'petugas_gudang_id' => 'required|exists:pegawai,id',
        'barang_id' => 'required|array',
        'jumlah_keluar' => 'required|array',
    ]);

    try {
        DB::transaction(function () use ($request, $bk) {
            $isChangingToSelesai = ($request->status_proses === 'selesai');

            $bk->update([
                'status_proses' => $request->status_proses,
                'gudang_id' => $request->gudang_id,
                'petugas_gudang_id' => $request->petugas_gudang_id,
                'catatan' => $request->catatan, 
                'updated_by' => Auth::id() ?? 1,
            ]);

            foreach ($request->barang_id as $index => $id_barang) {
                $qtyRealisasi = $request->jumlah_keluar[$index];
                $catatanItem = $request->catatan_barang[$index] ?? null;

                DetailBarangKeluar::where('barang_keluar_id', $bk->id)
                    ->where('barang_id', $id_barang)
                    ->update([
                        'jumlah_keluar' => $qtyRealisasi,
                        'catatan' => $catatanItem // Catatan Per Barang
                    ]);

                if ($isChangingToSelesai) {
                    $barang = Barang::lockForUpdate()->findOrFail($id_barang);
                    
                    if ($barang->stok < $qtyRealisasi) {
                        throw new \Exception("Stok '{$barang->nama_barang}' tidak cukup! (Sisa: {$barang->stok})");
                    }

                    $barang->decrement('stok', $qtyRealisasi);
                }
            }
        });

        return redirect()->route('distribusi_bantuan.barang_keluar.index')
                         ->with('success', 'Data distribusi dan stok berhasil diperbarui.');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
    }
}


    public function edit($id)
    {
        $data = BarangKeluar::with(['detailBarangKeluar.barang', 'pengajuanBarang'])->findOrFail($id);

        if (in_array($data->status_proses, ['selesai', 'dibatalkan'])) {
            return redirect()->route('distribusi_bantuan.barang_keluar.index')
                            ->with('error', 'Data final tidak dapat diubah.');
        }

        $gudang = Gudang::all();
        $pegawai = Pegawai::all();

        $barang = Barang::all(); 

        return view('distribusi_bantuan.barang_keluar.edit', compact('data', 'gudang', 'pegawai', 'barang'));
    }
}
