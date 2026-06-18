<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distribusi;
use Barryvdh\DomPDF\Facade\Pdf;

class BAController extends Controller
{
    public function cetak($id)
    {
        $distribusi = Distribusi::with([
            'bencana',
            'posko.desa',
            'detailDistribusis.detailBarangKeluar.barang'
        ])->findOrFail($id);

        return view(
            'management_distribusi.berita_acara.pdf',
            compact('distribusi')
        );
    }
    public function download($id)
{
    $distribusi = Distribusi::with([
        'bencana',
        'posko.desa',
        'detailDistribusis.detailBarangKeluar.barang'
    ])->findOrFail($id);

    $pdf = Pdf::loadView(
        'management_distribusi.berita_acara.pdf',
        compact('distribusi')
    );

    return $pdf->download(
        'Berita_Acara_Distribusi_'.$distribusi->id.'.pdf'
    );
}
}