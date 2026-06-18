<?php

namespace App\Http\Controllers;
use App\Models\PenerimaDistribusi;
use Illuminate\Http\Request;
use App\Models\DetailDistribusi;


class PenerimaDistribusiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index(Request $request)
{
    $query = PenerimaDistribusi::with([
        'detailDistribusi.distribusi.posko',
        'detailDistribusi.distribusi.bencana'
    ]);

    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where('nama_penerima', 'like', "%{$search}%")
              ->orWhere('jabatan', 'like', "%{$search}%")
              ->orWhere('instansi', 'like', "%{$search}%")
              ->orWhere('alamat', 'like', "%{$search}%")
              ->orWhere('no_hp', 'like', "%{$search}%")

              ->orWhereHas('detailDistribusi.distribusi.posko', function ($qq) use ($search) {
                    $qq->where('nama_posko', 'like', "%{$search}%");
              })

              ->orWhereHas('detailDistribusi.distribusi.bencana', function ($qq) use ($search) {
                    $qq->where('nama_bencana', 'like', "%{$search}%");
              });

        });

    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $data = $query
            ->latest()
            ->get();

    return view(
        'management_distribusi.penerima_distribusi.index',
        compact('data')
    );
}
    public function create()
    {
        $detailDistribusi = DetailDistribusi::with([
            'distribusi.posko',
            'distribusi.bencana'
        ])->get();

        return view(
            'management_distribusi.penerima_distribusi.create',
            compact('detailDistribusi')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $request->validate([
        'detail_distribusi_id' => 'required',
        'nama_penerima'        => 'required|max:100',
        'jabatan'              => 'required|max:100',
        'instansi'             => 'required|max:100',
        'alamat'               => 'required|max:150',
        'no_hp'                => 'required|max:15',
        'status'               => 'required',
    ]);

    $detailDistribusi = DetailDistribusi::with([
        'distribusi.posko'
    ])->find($request->detail_distribusi_id);

    if (!$detailDistribusi) {
        return back()
            ->withInput()
            ->with('error', 'Detail distribusi tidak ditemukan.');
    }

    PenerimaDistribusi::create([
        'detail_distribusi_id' => $request->detail_distribusi_id,
        'nama_penerima'        => $request->nama_penerima,
        'jabatan'              => $request->jabatan,
        'instansi'             => $request->instansi,
        'alamat'               => $request->alamat,
        'no_hp'                => $request->no_hp,

        // Otomatis isi nama posko
        'nama_posko'           => $detailDistribusi->distribusi->posko->nama_posko,

        'status'               => $request->status,
    ]);

    return redirect()
        ->route('admin.management_distribusi.penerima.index')
        ->with('success', 'Data penerima berhasil ditambahkan.');
}
    /**
     * Display the specified resource.
     */
    public function show(PenerimaDistribusi $penerimaDistribusi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = PenerimaDistribusi::where('penerima_id', $id)->firstOrFail();

        $detailDistribusi = DetailDistribusi::with([
            'distribusi.posko',
            'distribusi.bencana'
        ])->get();

        return view(
            'management_distribusi.penerima_distribusi.edit',
            compact('data', 'detailDistribusi')
        );
    }

public function update(Request $request, $id)
{
    $request->validate([
        'detail_distribusi_id' => 'required|exists:detail_distribusi,id',
        'nama_penerima'        => 'required|max:100',
        'jabatan'              => 'required|max:100',
        'instansi'             => 'required|max:100',
        'alamat'               => 'required|max:150',
        'no_hp'                => 'required|max:15',
        'status'               => 'required|in:Aktif,Tidak Aktif',
    ]);

    // Ambil detail distribusi beserta data posko
    $detailDistribusi = DetailDistribusi::with([
        'distribusi.posko'
    ])->find($request->detail_distribusi_id);

    if (!$detailDistribusi) {
        return back()
            ->withInput()
            ->with('error', 'Detail distribusi tidak ditemukan.');
    }

    // Cari data penerima
    $penerima = PenerimaDistribusi::where(
        'penerima_id',
        $id
    )->firstOrFail();

    // Update data
    $penerima->update([
        'detail_distribusi_id' => $request->detail_distribusi_id,
        'nama_penerima'        => $request->nama_penerima,
        'jabatan'              => $request->jabatan,
        'instansi'             => $request->instansi,
        'alamat'               => $request->alamat,
        'no_hp'                => $request->no_hp,

        // Otomatis ambil nama posko dari relasi
        'nama_posko'           => $detailDistribusi->distribusi->posko->nama_posko,

        'status'               => $request->status,
    ]);

    return redirect()
        ->route('admin.management_distribusi.penerima.index')
        ->with('success', 'Data penerima berhasil diperbarui.');
}

    /**
     * Remove the specified resource from storage.
     */
public function destroy($id)
{
    $data = PenerimaDistribusi::findOrFail($id);

    $data->delete();

        return redirect()->route('admin.management_distribusi.penerima.index')->with('success','Data berhasil dihapus.');
}
}
