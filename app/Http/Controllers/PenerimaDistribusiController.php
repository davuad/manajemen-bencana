<?php

namespace App\Http\Controllers;
use App\Models\PenerimaDistribusi;
use Illuminate\Http\Request;

class PenerimaDistribusiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = PenerimaDistribusi::query();

    // search
    if ($request->search) {
        $query->where('nama_penerima', 'like', '%'.$request->search.'%')
              ->orWhere('instansi', 'like', '%'.$request->search.'%')
              ->orWhere('alamat', 'like', '%'.$request->search.'%');
    }

    // filter status
    if ($request->status) {
        $query->where('status', $request->status);
    }

    $data = $query->get(); // ambil data

    return view('penerima.index', compact('data'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('penerima.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    PenerimaDistribusi::create([
        'detail_distribusi_id' => $request->detail_distribusi_id,
        'nama_penerima' => $request->nama_penerima,
        'jabatan' => $request->jabatan,
        'instansi' => $request->instansi,
        'no_hp' => $request->no_hp,
        'alamat' => $request->alamat,
        'nama_posko' => $request->nama_posko,
        'status' => $request->status ?? 'Aktif',
    ]);

    return redirect('/penerima')->with('success', 'Data berhasil ditambahkan');
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
        $data = PenerimaDistribusi::where('penerima_id', $id)->first();

        return view('penerima.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
    PenerimaDistribusi::where('penerima_id', $id)->update([
        'detail_distribusi_id' => $request->detail_distribusi_id,
        'nama_penerima' => $request->nama_penerima,
        'jabatan' => $request->jabatan,
        'instansi' => $request->instansi,
        'no_hp' => $request->no_hp,
        'alamat' => $request->alamat,
        'nama_posko' => $request->nama_posko,
        'status' => $request->status,
    ]);

    return redirect('/penerima')
        ->with('success', 'Data berhasil diupdate');
}

    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id)
{
    PenerimaDistribusi::where('penerima_id', $id)->delete();

    return redirect()->route('penerima.index')
        ->with('success', 'Data berhasil dihapus');
}
}
