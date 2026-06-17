<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengambilan;
use App\Models\Petugas;
use App\Models\Bencana;
use App\Models\Posko;
use App\Models\Barang;

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

                $q->where(
                    'tujuan',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas('petugas', function ($p) use ($search) {

                    $p->where(
                        'nama_petugas',
                        'like',
                        "%{$search}%"
                    );

                })

                ->orWhereHas('posko', function ($p) use ($search) {

                    $p->where(
                        'nama_posko',
                        'like',
                        "%{$search}%"
                    );

                });

            });
        }

        $data = $query
            ->latest('id')
            ->get()

            // grouping
            ->groupBy(function ($item) {

                return
                    $item->bencana_id .
                    '-' .
                    $item->petugas_id .
                    '-' .
                    $item->tanggal_pengambilan .
                    '-' .
                    $item->posko_id .
                    '-' .
                    $item->tujuan;
            })

            ->map(function ($group) {

                return $group->first();

            });

        return view(
            'manajemen_barang.pengambilan.index',
            compact('data')
        );
    }

    public function create()
    {
        $barang  = Barang::all();

        $bencana = Bencana::all();

        $petugas = Petugas::whereNull(
            'deleted_at'
        )->get();

        $posko   = Posko::all();

        return view(
            'manajemen_barang.pengambilan.create',
            compact(
                'barang',
                'bencana',
                'petugas',
                'posko'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([

            'bencana_id' => 'required|exists:bencana,id',
            'petugas_id' => 'required|exists:petugas,id',
            'posko_id' => 'required|exists:posko,id',
            'tanggal_pengambilan' => 'required|date',
            'tujuan' => 'required|max:100',

            'barang_id.*' => 'nullable|exists:barang,id',
            'jumlah_ambil.*' => 'nullable|integer|min:1',

        ]);

        $adaBarang = false;

        foreach ($request->barang_id as $index => $barangId) {

            if (
                empty($barangId) ||
                empty($request->jumlah_ambil[$index])
            ) {
                continue;
            }

            $adaBarang = true;

            $jumlah =
                $request->jumlah_ambil[$index];

            $barang = Barang::findOrFail(
                $barangId
            );

            // cek stok
            if ($jumlah > $barang->stok) {

                return back()->withErrors([

                    'jumlah_ambil' =>
                        'Jumlah melebihi stok untuk '
                        . $barang->nama_barang

                ])->withInput();
            }

            // simpan
            Pengambilan::create([

                'barang_id' => $barangId,
                'bencana_id' => $request->bencana_id,
                'petugas_id' => $request->petugas_id,
                'posko_id' => $request->posko_id,

                'tanggal_pengambilan' =>
                    $request->tanggal_pengambilan,

                'jumlah_ambil' => $jumlah,

                'tujuan' => $request->tujuan,

                'status' => 'Ditangani'

            ]);

            // kurangi stok
            $barang->stok -= $jumlah;
            $barang->save();
        }

        // minimal 1 barang
        if (!$adaBarang) {

            return back()->withErrors([

                'barang_id' =>
                    'Minimal pilih 1 barang'

            ])->withInput();
        }

        return redirect()
            ->route(
                'manajemen_barang.pengambilan.index'
            )
            ->with(
                'success',
                'Data pengambilan berhasil ditambahkan'
            );
    }

    public function edit($id)
    {
        $data = Pengambilan::findOrFail($id);

        $barang = Barang::all();

        $bencana = Bencana::all();

        $petugas = Petugas::whereNull(
            'deleted_at'
        )->get();

        $posko = Posko::all();

        return view(
            'manajemen_barang.pengambilan.edit',
            compact(
                'data',
                'barang',
                'bencana',
                'petugas',
                'posko'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate([

            'bencana_id' => 'required|exists:bencana,id',
            'petugas_id' => 'required|exists:petugas,id',
            'posko_id' => 'required|exists:posko,id',
            'tanggal_pengambilan' => 'required|date',
            'tujuan' => 'required|max:100',

            'status' =>
                'required|in:Ditangani,Selesai,Dibatalkan',

            'barang_id.*' =>
                'nullable|exists:barang,id',

            'jumlah_ambil.*' =>
                'nullable|integer|min:0',

        ]);

        $dataLama = Pengambilan::findOrFail($id);

        // ambil data group lama
        $groupData = Pengambilan::where(
                'tanggal_pengambilan',
                $dataLama->tanggal_pengambilan
            )
            ->where(
                'petugas_id',
                $dataLama->petugas_id
            )
            ->where(
                'tujuan',
                $dataLama->tujuan
            )
            ->where(
                'posko_id',
                $dataLama->posko_id
            )
            ->get();

        // kembalikan stok lama
        foreach ($groupData as $old) {

            if (
                $old->status != 'Dibatalkan'
            ) {

                $barangOld = Barang::find(
                    $old->barang_id
                );

                if ($barangOld) {

                    $barangOld->stok +=
                        $old->jumlah_ambil;

                    $barangOld->save();
                }
            }
        }

        // hapus data lama
        Pengambilan::where(
                'tanggal_pengambilan',
                $dataLama->tanggal_pengambilan
            )
            ->where(
                'petugas_id',
                $dataLama->petugas_id
            )
            ->where(
                'tujuan',
                $dataLama->tujuan
            )
            ->where(
                'posko_id',
                $dataLama->posko_id
            )
            ->delete();

        $adaBarang = false;

        $statusDibatalkan =
            $request->status == 'Dibatalkan';

        // simpan ulang
        foreach ($request->barang_id as $index => $barangId) {

            if (empty($barangId)) {
                continue;
            }

            $adaBarang = true;

            $jumlah =
                $request->jumlah_ambil[$index]
                ?? 0;

            // jika dibatalkan
            if ($statusDibatalkan) {

                $jumlah = 0;
            }

            $barang = Barang::findOrFail(
                $barangId
            );

            // cek stok
            if (
                !$statusDibatalkan &&
                $jumlah > $barang->stok
            ) {

                return back()->withErrors([

                    'jumlah_ambil' =>
                        'Jumlah melebihi stok untuk '
                        . $barang->nama_barang

                ])->withInput();
            }

            // create ulang
            Pengambilan::create([

                'barang_id' => $barangId,
                'bencana_id' => $request->bencana_id,
                'petugas_id' => $request->petugas_id,
                'posko_id' => $request->posko_id,

                'tanggal_pengambilan' =>
                    $request->tanggal_pengambilan,

                'jumlah_ambil' => $jumlah,

                'tujuan' => $request->tujuan,

                'status' => $request->status,

            ]);

            // kurangi stok
            if (!$statusDibatalkan) {

                $barang->stok -= $jumlah;
                $barang->save();
            }
        }

        if (
            !$adaBarang &&
            !$statusDibatalkan
        ) {

            return back()->withErrors([

                'barang_id' =>
                    'Minimal pilih 1 barang'

            ])->withInput();
        }

        return redirect()
            ->route(
                'manajemen_barang.pengambilan.index'
            )
            ->with(
                'success',
                'Data pengambilan berhasil diupdate'
            );
    }

    public function batal($id)
    {
        $data = Pengambilan::findOrFail($id);

        $groupData = Pengambilan::where(
                'tanggal_pengambilan',
                $data->tanggal_pengambilan
            )
            ->where(
                'petugas_id',
                $data->petugas_id
            )
            ->where(
                'tujuan',
                $data->tujuan
            )
            ->where(
                'posko_id',
                $data->posko_id
            )
            ->get();

        foreach ($groupData as $item) {

            // kembalikan stok
            if (
                $item->status != 'Dibatalkan'
            ) {

                $barang = Barang::find(
                    $item->barang_id
                );

                if ($barang) {

                    $barang->stok +=
                        $item->jumlah_ambil;

                    $barang->save();
                }
            }

            // ubah status
            $item->update([

                'status' => 'Dibatalkan',
                'jumlah_ambil' => 0

            ]);
        }

        return redirect()
            ->route(
                'manajemen_barang.pengambilan.index'
            )
            ->with(
                'success',
                'Pengambilan berhasil dibatalkan'
            );
    }

    public function show($id)
    {
        $data = Pengambilan::with([
            'barang',
            'bencana',
            'petugas',
            'posko'
        ])->findOrFail($id);

        return view(
            'manajemen_barang.pengambilan.show',
            compact('data')
        );
    }
}