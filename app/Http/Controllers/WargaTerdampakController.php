<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Bencana;
use App\Models\WargaTerdampak;
use Illuminate\Http\Request;

class WargaTerdampakController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $filterDesa = $request->desa;
        $filterBencana = $request->bencana;
        $filterStatus = $request->status_penyaluran;

        $query = WargaTerdampak::with(['desa', 'bencana']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('no_kk', 'like', '%' . $search . '%')
                    ->orWhere('nik_kepala_keluarga', 'like', '%' . $search . '%')
                    ->orWhere('nama_kepala_keluarga', 'like', '%' . $search . '%')
                    ->orWhere('alamat', 'like', '%' . $search . '%');
            });
        }

        if (!empty($filterDesa)) {
            $query->where('desa_id', $filterDesa);
        }

        if (!empty($filterBencana)) {
            $query->where('bencana_id', $filterBencana);
        }

        if (!empty($filterStatus)) {
            $query->where('status_penyaluran', $filterStatus);
        }

        $warga = $query->orderBy('id', 'asc')
            ->paginate(6)
            ->withQueryString();

        $listDesa = Desa::orderBy('nama_desa', 'asc')->get();
        $listBencana = Bencana::orderBy('nama_bencana', 'asc')->get();

        $listStatus = [
            'Belum diproses',
            'Proses Penyaluran',
            'Sudah disalurkan',
        ];

        return view('management_warga.warga_terdampak.index', compact(
            'warga',
            'search',
            'filterDesa',
            'filterBencana',
            'filterStatus',
            'listDesa',
            'listBencana',
            'listStatus'
        ));
    }

    public function create()
    {
        $desa = Desa::orderBy('nama_desa', 'asc')->get();
        $bencana = Bencana::orderBy('nama_bencana', 'asc')->get();

        return view('management_warga.warga_terdampak.create', compact('desa', 'bencana'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|string|max:20|unique:warga_terdampak,no_kk',
            'nik_kepala_keluarga' => 'required|string|max:20|unique:warga_terdampak,nik_kepala_keluarga',
            'nama_kepala_keluarga' => 'required|string|max:50',
            'alamat' => 'required|string',
            'desa_id' => 'required|exists:desa,id',
            'bencana_id' => 'required|exists:bencana,id',
            'jumlah_anggota' => 'required|integer|min:1',
            'tanggal_pendataan' => 'required|date',
            'jenis_bantuan' => 'required|in:Bantuan Saat Bencana,Bantuan Pasca Bencana',
            'status_penyaluran' => 'required|in:Belum diproses,Proses Penyaluran,Sudah disalurkan',
            'tanggal_penyaluran' => 'nullable|date',
        ]);

        WargaTerdampak::create([
            'no_kk' => $request->no_kk,
            'nik_kepala_keluarga' => $request->nik_kepala_keluarga,
            'nama_kepala_keluarga' => $request->nama_kepala_keluarga,
            'alamat' => $request->alamat,
            'desa_id' => $request->desa_id,
            'bencana_id' => $request->bencana_id,
            'jumlah_anggota' => $request->jumlah_anggota,
            'tanggal_pendataan' => $request->tanggal_pendataan,
            'jenis_bantuan' => $request->jenis_bantuan,
            'status_penyaluran' => $request->status_penyaluran,
            'tanggal_penyaluran' => $request->tanggal_penyaluran,
        ]);

        return redirect()->route('admin.warga.index')
            ->with('success', 'Data warga terdampak berhasil ditambahkan.');
    }

    public function detail($id)
    {
        $warga = WargaTerdampak::with(['desa', 'bencana'])->findOrFail($id);

        return view('management_warga.warga_terdampak.detail', compact('warga'));
    }

    public function edit($id)
    {
        $warga = WargaTerdampak::findOrFail($id);
        $desa = Desa::orderBy('nama_desa', 'asc')->get();
        $bencana = Bencana::orderBy('nama_bencana', 'asc')->get();

        return view('management_warga.warga_terdampak.edit', compact('warga', 'desa', 'bencana'));
    }

    public function update(Request $request, $id)
    {
        $warga = WargaTerdampak::findOrFail($id);

        $request->validate([
            'no_kk' => 'required|string|max:20|unique:warga_terdampak,no_kk,' . $warga->id,
            'nik_kepala_keluarga' => 'required|string|max:20|unique:warga_terdampak,nik_kepala_keluarga,' . $warga->id,
            'nama_kepala_keluarga' => 'required|string|max:50',
            'alamat' => 'required|string',
            'desa_id' => 'required|exists:desa,id',
            'bencana_id' => 'required|exists:bencana,id',
            'jumlah_anggota' => 'required|integer|min:1',
            'tanggal_pendataan' => 'required|date',
            'jenis_bantuan' => 'required|in:Bantuan Saat Bencana,Bantuan Pasca Bencana',
            'status_penyaluran' => 'required|in:Belum diproses,Proses Penyaluran,Sudah disalurkan',
            'tanggal_penyaluran' => 'nullable|date',
        ]);

        $warga->update([
            'no_kk' => $request->no_kk,
            'nik_kepala_keluarga' => $request->nik_kepala_keluarga,
            'nama_kepala_keluarga' => $request->nama_kepala_keluarga,
            'alamat' => $request->alamat,
            'desa_id' => $request->desa_id,
            'bencana_id' => $request->bencana_id,
            'jumlah_anggota' => $request->jumlah_anggota,
            'tanggal_pendataan' => $request->tanggal_pendataan,
            'jenis_bantuan' => $request->jenis_bantuan,
            'status_penyaluran' => $request->status_penyaluran,
            'tanggal_penyaluran' => $request->tanggal_penyaluran,
        ]);

        return redirect()->route('admin.warga.index')
            ->with('success', 'Data warga terdampak berhasil diupdate.');
    }

    public function delete($id)
    {
        // Diubah menjadi find() untuk mencegah crash ganda
        $warga = WargaTerdampak::find($id);

        if ($warga) {
            $warga->delete();
            return redirect()->route('admin.warga.index')
                ->with('success', 'Data warga terdampak berhasil dihapus.');
        }

        return redirect()->route('admin.warga.index')
            ->with('success', 'Data warga terdampak sudah berhasil dihapus.');
    }

    public function ubahStatus($id)
    {
        // Diubah menjadi find() agar aman jika status di-klik beruntun
        $warga = WargaTerdampak::find($id);

        if (!$warga) {
            return redirect()->route('admin.warga.index')
                ->with('success', 'Proses ubah status selesai.');
        }

        if ($warga->status_penyaluran === 'Belum diproses') {
            $warga->status_penyaluran = 'Proses Penyaluran';

            if (empty($warga->tanggal_penyaluran)) {
                $warga->tanggal_penyaluran = now()->toDateString();
            }

            $warga->save();

            return redirect()->route('admin.warga.index')
                ->with('success', 'Status berhasil diubah menjadi Proses Penyaluran.');
        }

        if ($warga->status_penyaluran === 'Proses Penyaluran') {
            $warga->status_penyaluran = 'Sudah disalurkan';

            if (empty($warga->tanggal_penyaluran)) {
                $warga->tanggal_penyaluran = now()->toDateString();
            }

            $warga->save();

            return redirect()->route('admin.warga.index')
                ->with('success', 'Status berhasil diubah menjadi Sudah Disalurkan.');
        }

        return redirect()->route('admin.warga.index')
            ->with('success', 'Status sudah final dan tidak bisa diubah lagi.');
    }
}
