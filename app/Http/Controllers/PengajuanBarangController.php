<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; 
use App\Models\PengajuanBarang;
use App\Models\Bencana;
use App\Models\Pegawai;
use App\Models\Barang;
use App\Models\DetailPengajuanBarang;
use App\Models\BarangKeluar;
use App\Models\DetailBarangKeluar;
use App\Models\User; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 
use App\Imports\PengajuanImport; 
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class PengajuanBarangController extends Controller
{


public function index(Request $request)
{
    $query = PengajuanBarang::with(['pegawai', 'bencana.kategoriBencana', 'bencana.desa', 'creator']);

    if ($request->nama_bencana) {
        $query->whereHas('bencana', function($q) use ($request) {
            $q->whereHas('kategoriBencana', function($q2) use ($request) {
                $q2->where('nama_kategori', 'like', '%' . $request->nama_bencana . '%');
            })->orWhereHas('desa', function($q2) use ($request) {
                $q2->where('nama_desa', 'like', '%' . $request->nama_bencana . '%');
            });
        });
    }

    if ($request->tahun) {
        $query->whereYear('tgl_pengajuan', $request->tahun);
    }

    if ($request->bulan) {
        $query->whereMonth('tgl_pengajuan', $request->bulan);
    }

    if ($request->status) {
        $query->where('status_pengajuan', $request->status);
    }

    if ($request->search) {
        $query->whereHas('pegawai', function($q) use ($request) {
            $q->where('nama_pegawai', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->export == 'print') {
        $data = $query->get();
        return view('distribusi_bantuan.pengajuan_barang.export', compact('data'));
    }

    $data = $query->latest()->paginate(10)->withQueryString();
    return view('distribusi_bantuan.pengajuan_barang.index', compact('data'));
}


    public function create()
    {
        $bencana = Bencana::with(['desa', 'kategoriBencana', 'pengaduan'])->get();
        $pegawai = Pegawai::all();
        $barang = Barang::all(); 

        return view('distribusi_bantuan.pengajuan_barang.create', compact('bencana', 'pegawai', 'barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bencana_id' => 'required|exists:bencana,id',
            'pegawai_id' => 'required|exists:pegawai,id',
            'tgl_pengajuan' => 'required|date',
            'barang_id' => 'required|array',
            'jumlah' => 'required|array',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $authId = Auth::id();
                if (!$authId) {
                    $firstUser = User::first();
                    $authId = $firstUser ? $firstUser->id : 1;
                }

                $pengajuan = PengajuanBarang::create([
                    'bencana_id' => $request->bencana_id,
                    'pegawai_id' => $request->pegawai_id,
                    'tgl_pengajuan' => $request->tgl_pengajuan,
                    'status_pengajuan' => 'pending', 
                    'keterangan' => $request->keterangan,
                    'created_by' => $authId,
                ]);

                foreach ($request->barang_id as $index => $id_barang) {
                    DetailPengajuanBarang::create([
                        'pengajuan_barang_id' => $pengajuan->id,
                        'barang_id' => $id_barang,
                        'jumlah' => $request->jumlah[$index],
                        'kategori_penerima' => $request->kategori_penerima[$index] ?? 'warga',
                    ]);
                }
            });

            return redirect()->route('distribusi_bantuan.pengajuan.index')->with('success', 'Data pengajuan berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal Simpan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $data = PengajuanBarang::with([
            'detailPengajuan.barang', 
            'bencana.kategoriBencana',
            'bencana.desa', 
            'pegawai'])->findOrFail($id);
        return view('distribusi_bantuan.pengajuan_barang.show', compact('data'));
    }

 
    public function edit($id)
    {
        $data = PengajuanBarang::findOrFail($id);

        // Kunci: Jika status sudah disetujui atau ditolak, jangan kasih edit
        if ($data->status_pengajuan !== 'pending') {
            return redirect()->route('distribusi_bantuan.pengajuan.index')
                            ->with('error', 'Gagal! Data yang sudah diproses tidak dapat diubah kembali.');
        }

        $bencana = Bencana::with(['desa', 'kategoriBencana'])->get();
        $pegawai = Pegawai::all();
        $barang = Barang::all();

        return view('distribusi_bantuan.pengajuan_barang.edit', compact('data', 'bencana', 'pegawai', 'barang'));
}


  
    public function update(Request $request, $id)
    {
        $request->validate([
            'bencana_id' => 'required',
            'pegawai_id' => 'required',
            'tgl_pengajuan' => 'required|date',
            'status_pengajuan' => 'required',
            'keterangan' => 'nullable|string',
            'catatan' => 'nullable|string',
            'barang_id' => 'required|array',
        ]);

        $pengajuan = PengajuanBarang::findOrFail($id);
        $oldStatus = $pengajuan->status_pengajuan;

        try {
            DB::transaction(function () use ($request, $pengajuan, $oldStatus) {
             
                $authId = Auth::id() ?? (User::first()->id ?? 1);

                $pengajuan->update([
                    'bencana_id' => $request->bencana_id,
                    'pegawai_id' => $request->pegawai_id,
                    'tgl_pengajuan' => $request->tgl_pengajuan,
                    'status_pengajuan' => $request->status_pengajuan,
                    'keterangan' => $request->keterangan,
                    'catatan' => $request->catatan,
                    'updated_by' => $authId,
                ]);

                $pengajuan->detailPengajuan()->delete();
                foreach ($request->barang_id as $index => $id_barang) {
                    DetailPengajuanBarang::create([
                        'pengajuan_barang_id' => $pengajuan->id,
                        'barang_id' => $id_barang,
                        'jumlah' => $request->jumlah[$index],
                        'kategori_penerima' => $request->kategori_penerima[$index] ?? 'warga',
                    ]);
                }

                if ($oldStatus !== 'disetujui' && $request->status_pengajuan === 'disetujui') {
                    $bk = BarangKeluar::create([
                        'pengajuan_barang_id' => $pengajuan->id,
                        'gudang_id'           => 1, // Default gudang
                        'petugas_gudang_id'   => $request->pegawai_id, // Default petugas
                        'tgl_keluar'          => now(),
                        'status_proses'       => 'diproses',
                    ]);

                    foreach ($pengajuan->detailPengajuan as $detail) {
                        DetailBarangKeluar::create([
                            'barang_keluar_id' => $bk->id,
                            'barang_id'        => $detail->barang_id,
                            'jumlah'           => $detail->jumlah,
                            'jumlah_keluar'    => $detail->jumlah,
                        ]);
                    }
                }
            });

            return redirect()->route('distribusi_bantuan.pengajuan.index')->with('success', 'Data diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Perbarui: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            PengajuanBarang::findOrFail($id)->delete();
            return redirect()->route('distribusi_bantuan.pengajuan.index')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Hapus: ' . $e->getMessage());
        }
    }



public function importExcel(Request $request)
{
    try {
        $request->validate(['file_excel' => 'required|mimes:xlsx,xls']);

        $import = new PengajuanImport;
        \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file_excel'));

        return response()->json([
            'success' => true,
            'data' => $import->importData
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false, 
            'message' => 'Error di Baris: ' . $e->getLine() . ' - ' . $e->getMessage()
        ], 500);
    }
}


public function previewImport(Request $request)
{
    $request->validate(['file_excel' => 'required|mimes:xlsx,xls']);

    $import = new PengajuanImport;
    Excel::import($import, $request->file('file_excel'));

    $data = $import->importData;
    
    session(['temp_import_data' => $data]);

    return view('distribusi_bantuan.pengajuan_barang.import_preview', compact('data'));
}

public function storeImport(Request $request)
{
    $rawData = session('temp_import_data');

    if (!$rawData || count($rawData) == 0) {
        return redirect()->route('distribusi_bantuan.pengajuan.create')
                         ->with('error', 'Gagal! Sesi import kadaluarsa.');
    }

    try {
        DB::transaction(function () use ($rawData) {
            $authId = Auth::id() ?? (User::first()->id ?? 1);

            $groupedData = collect($rawData)->groupBy(function ($item) {
                return $item['desa_nama'] . '|' . $item['kategori_nama'] . '|' . $item['tanggal'] . '|' . $item['pegawai_id'];
            });

            foreach ($groupedData as $key => $items) {
         
                $firstItem = $items->first();

                $desa = \App\Models\Desa::firstOrCreate(
                    ['nama_desa' => $firstItem['desa_nama']],
                    ['kecamatan' => $firstItem['kecamatan'], 'nama_kades' => '-', 'kontak_kades' => '-']
                );

                $kategori = \App\Models\KategoriBencana::firstOrCreate(
                    ['nama_kategori' => $firstItem['kategori_nama']]
                );

                $bencana = \App\Models\Bencana::firstOrCreate([
                    'desa_id' => $desa->id,
                    'kategori_id' => $kategori->id,
                    'tanggal' => $firstItem['tanggal']
                ], ['tingkat_kerusakan' => 'Sedang', 
                    'jumlah_korban'     => $firstItem['jumlah_korban']
                    ]);

                $pengajuan = PengajuanBarang::create([
                    'bencana_id' => $bencana->id,
                    'pegawai_id' => $firstItem['pegawai_id'],
                    'tgl_pengajuan' => now(),
                    'status_pengajuan' => 'pending',
                    'keterangan' => 'Import Massal (Multiple Items)',
                    'created_by' => $authId,
                ]);

                foreach ($items as $item) {
                    if ($item['barang_id']) {
                        DetailPengajuanBarang::create([
                            'pengajuan_barang_id' => $pengajuan->id,
                            'barang_id' => $item['barang_id'],
                            'jumlah' => $item['jumlah'],
                            'kategori_penerima' => $item['kategori_penerima'] ?? 'warga',
                        ]);
                    }
                }
            }
        });

        session()->forget('temp_import_data');
        return redirect()->route('distribusi_bantuan.pengajuan.index')
                         ->with('success', 'Berhasil! Data telah dikelompokkan dan diimpor secara otomatis.');
                         
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

}
