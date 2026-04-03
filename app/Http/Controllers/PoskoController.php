<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PoskoController extends Controller
{
    public function index()
    {
        $poskos = [
            [
                'id' => 'POS-1',
                'nama' => 'Posko Cilacap Tengah 1',
                'lokasi' => 'Cilacap Tengah',
                'desa' => 'Desa A',
                'tanggal' => '10 Januari 2026',
            ],
            [
                'id' => 'POS-2',
                'nama' => 'Posko Cilacap Selatan 1',
                'lokasi' => 'Cilacap Selatan',
                'desa' => 'Desa B',
                'tanggal' => '27 Januari 2026',
            ],
        ];

        return view('management_posko.posko.index', compact('poskos'));
    }
}
