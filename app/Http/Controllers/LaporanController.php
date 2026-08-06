<?php

namespace App\Http\Controllers;

use App\Models\Bencana;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Bencana::with([
            'kategori',
            'desa',
            'pengaduan'
        ]);

        // filter tanggal
        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $query->whereBetween('tanggal', [
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ]);
        } elseif ($request->tanggal_mulai) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        } elseif ($request->tanggal_selesai) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        // search nama desa
        if ($request->search) {
            $query->whereHas('desa', function ($q) use ($request) {
                $q->where('nama_desa', 'like', '%' . $request->search . '%');
            });
        }

        // filter status
        if ($request->status) {
            $query->whereHas('pengaduan', function ($q) use ($request) {
                $q->where('status_pengaduan', $request->status);
            });
        }

        $laporan = $query->paginate(10)->withQueryString();

        return view('laporan.index', [
            'laporan' => $laporan
        ]);
    }

    public function pdf(Request $request)
    {
        $query = Bencana::with([
            'kategori',
            'desa',
            'pengaduan'
        ]);

        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $query->whereBetween('tanggal', [
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ]);
        } elseif ($request->tanggal_mulai) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        } elseif ($request->tanggal_selesai) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        if ($request->search) {
            $query->whereHas('desa', function ($q) use ($request) {
                $q->where('nama_desa', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status) {
            $query->whereHas('pengaduan', function ($q) use ($request) {
                $q->where('status_pengaduan', $request->status);
            });
        }

        $laporan = $query->get();

        $pdf = Pdf::loadView('laporan.pdf', [
            'laporan' => $laporan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'search' => $request->search,
            'status' => $request->status
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-bencana.pdf');
    }
    public function pdfDetail($id)
    {
        $data = Bencana::with([
            // MASTER
            'kategori',
            'desa',

            // PENGADUAN + POSKO + DAPUR UMUM
            'pengaduan.poskos.dapurUmum',


            // DISTRIBUSI
            'distribusis.detailDistribusis.detailBarangKeluar.barang'
        ])->findOrFail($id);

        $pdf = Pdf::loadView(
            'laporan.pdf_detail',
            compact('data')
        )->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-detail.pdf');
    }
}
