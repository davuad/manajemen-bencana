<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PenjemputanAnak;
use App\Models\AnakTerpisah;
use App\Models\Penjemput;
use App\Models\Petugas;

class PenjemputanAnakController extends Controller
{
    public function index(Request $request)
    {
        $query = AnakTerpisah::with(['penjemputan.penjemput', 'penjemputan.petugas']);

        if ($request->filled('search')) {
            $query->where('nama_anak', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            if ($request->status == 'valid') {
                $query->where('status_anak', 'sudah_dijemput');
            } else {
                $query->where('status_anak', '!=', 'sudah_dijemput');
            }
        }

        $data = $query->get();

        return view('management_korban.penjemputan_anak.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'anak_id' => 'required|exists:anak_terpisah,id',
            'petugas_id' => 'required|exists:petugas,id',
            'tanggal_penjemputan' => 'required|date',
            'status_verifikasi' => 'required|in:menunggu,valid,ditolak',
            'catatan' => 'nullable|string',

            'nama_penjemput' => 'required|string|max:255',
            'nik' => 'required|string|max:30',
            'hubungan_dengan_anak' => 'required|string|max:100',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',

            'bukti_dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'berita_acara' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $anak = AnakTerpisah::findOrFail($request->anak_id);

        if ($anak->status_anak === 'sudah_dijemput') {
            return back()->with('error', 'Anak sudah dijemput');
        }

        DB::beginTransaction();

        try {
            $penjemput = Penjemput::create([
                'nama_penjemput' => $request->nama_penjemput,
                'nik' => $request->nik,
                'hubungan_dengan_anak' => $request->hubungan_dengan_anak,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
            ]);

            $data = [
                'anak_id' => $request->anak_id,
                'penjemput_id' => $penjemput->id,
                'petugas_id' => $request->petugas_id,
                'tanggal_penjemputan' => $request->tanggal_penjemputan,
                'status_verifikasi' => $request->status_verifikasi,
                'catatan' => $request->catatan,
            ];

            if ($request->hasFile('bukti_dokumen')) {
                $data['bukti_dokumen'] = $request->file('bukti_dokumen')->store('bukti_dokumen', 'public');
            }

            if ($request->hasFile('berita_acara')) {
                $data['berita_acara'] = $request->file('berita_acara')->store('berita_acara', 'public');
            }

            PenjemputanAnak::create($data);

            if ($request->status_verifikasi === 'valid') {
                $anak->update(['status_anak' => 'sudah_dijemput']);
            }

            DB::commit();

            return redirect()->route('admin.penjemputan.index')
                ->with('success', 'Penjemputan berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $penjemputan = PenjemputanAnak::with(['anak', 'penjemput', 'petugas'])
            ->findOrFail($id);

        return view('management_korban.penjemputan_anak.show', compact('penjemputan'));
    }

    public function formJemput($anak_id)
    {
        $anak = AnakTerpisah::findOrFail($anak_id);

        if ($anak->status_anak == 'sudah_dijemput') {
            return redirect()->route('admin.penjemputan.index')
                ->with('error', 'Anak sudah dijemput');
        }

        $petugas = Petugas::all();

        return view('management_korban.penjemputan_anak.jemput', compact('anak', 'petugas'));
    }
}