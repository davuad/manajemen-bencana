<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\SumberBarangMasuk;
use App\Models\Gudang;
use App\Models\Pegawai;
use App\Models\DetailBarangMasuk;
use App\Models\Barang;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $query = BarangMasuk::with(['sumber', 'gudang', 'pegawai']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id_barang_masuk', 'like', "%{$search}%")
                    ->orWhere('no_dokumen', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('sumber', function ($s) use ($search) {
                        $s->where('nama_sumber', 'like', "%{$search}%");
                    });
            });
        }

        if ($bulan) {
            $query->whereMonth('tgl_masuk', $bulan);
        }

        if ($tahun) {
            $query->whereYear('tgl_masuk', $tahun);
        }

        $data = $query->get();

        return view('barang_masuk.index', compact(
            'data',
            'search',
            'bulan',
            'tahun'
        ));
    }

    public function create()
    {
        $gudang = Gudang::all();
        $pegawai = Pegawai::all();
        $sumber = SumberBarangMasuk::all();
        $barang = Barang::all();

        return view('barang_masuk.create', compact('sumber','gudang','pegawai','barang'));
    }

    public function store(Request $request)
    {
        // Simpan header
        $barangMasuk = BarangMasuk::create([
            'id_barang_masuk' => 'BM' . time(),
            'tgl_masuk' => $request->tgl_masuk,
            'id_sumber' => $request->id_sumber,
            'id_gudang' => $request->id_gudang,
            'id_pegawai' => $request->id_pegawai,
            'status' => $request->status,
            'no_dokumen' => $request->no_dokumen,
            'keterangan' => $request->keterangan
        ]);

        // Simpan detail barang (loop)
        if ($request->barang) {
            foreach ($request->barang as $i => $barang) {
                DetailBarangMasuk::create([
                    'id_detail_barang_masuk' => 'DBM' . rand(1000,9999) . $i,
                    'id_barang_masuk' => $barangMasuk->id_barang_masuk,
                    'id_barang' => $barang, 
                    'jumlah' => $request->jumlah[$i],
                    'satuan' => $request->satuan[$i],
                    'kondisi_barang' => $request->kondisi[$i],
                ]);
            }
        }

        return redirect()->route('admin.barang-masuk.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function show($id)
    {
        $data = BarangMasuk::with(['sumber','gudang','pegawai', 'detail.barang'])
                ->findOrFail($id);

        return view('barang_masuk.show', compact('data'));
    }

    public function edit($id)
    {
        $data = BarangMasuk::with('detail')->findOrFail($id);

        $sumber = SumberBarangMasuk::all();
        $gudang = Gudang::all();
        $pegawai = Pegawai::all();
        $barang = Barang::all();

        return view(
            'barang_masuk.edit',
            compact('data', 'sumber', 'gudang', 'pegawai', 'barang')
        );
    }

    public function update(Request $request, $id)
    {
        $data = BarangMasuk::findOrFail($id);

        // update header
        $data->update([
            'tgl_masuk'   => $request->tgl_masuk,
            'id_sumber'   => $request->id_sumber,
            'id_gudang'   => $request->id_gudang,
            'id_pegawai'  => $request->id_pegawai,
            'status'      => $request->status,
            'no_dokumen'  => $request->no_dokumen,
            'keterangan'  => $request->keterangan,
        ]);

        // update detail
        if ($request->detail_id) {

            foreach ($request->detail_id as $i => $detailId) {

                $detail = DetailBarangMasuk::find($detailId);

                if ($detail) {
                    $detail->update([
                        'jumlah' => $request->jumlah[$i],
                        'satuan' => $request->satuan[$i],
                        'kondisi_barang' => $request->kondisi[$i],
                    ]);
                }
            }
        }

        return redirect()->route('admin.barang-masuk.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $data = BarangMasuk::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.barang-masuk.index')
            ->with('success', 'Data berhasil dihapus');
    }
}