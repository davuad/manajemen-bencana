<?php

namespace App\Http\Controllers;

use App\Models\Relawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RelawanController extends Controller
{
   public function index()
{
    $relawan = Relawan::all();
    return view('management_relawan.index', compact('relawan'));
}

public function create()
{
    return view('management_relawan.create');
}
    public function store(Request $request)
    {
        $request->validate([
            'nama_relawan' => 'required',
    'jenis_psks' => 'required',
    'kecamatan' => 'required',
    'no_hp' => 'required',
    'alamat' => 'required',
    'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $foto = null;

if ($request->hasFile('foto')) {
    $foto = $request->file('foto')->store('relawan', 'public');
}

        Relawan::create([
            'nama_relawan' => $request->nama_relawan,
            'jenis_psks'   => $request->jenis_psks,
            'kecamatan'    => $request->kecamatan,
            'no_hp'        => $request->no_hp,
            'alamat'       => $request->alamat,
            'foto' => $foto,
        ]);

        return redirect()->route('admin.management_pegawai.relawan.index')
    ->with('success', 'data relawan berhasil ditambahkan');
    }

    public function edit($id)
{
    $relawan = Relawan::findOrFail($id);
    return view('management_relawan.edit', compact('relawan'));
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_relawan' => 'required|max:100',
            'jenis_psks'   => 'required',
            'kecamatan'    => 'required|max:100',
            'no_hp'        => 'required|max:20',
            'alamat'       => 'required',
             'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $relawan = Relawan::findOrFail($id);

        if ($request->hasFile('foto')) {

    if ($relawan->foto) {
        Storage::disk('public')->delete($relawan->foto);
    }

    $relawan->foto = $request->file('foto')->store('relawan', 'public');
}

        $relawan->update([
            'nama_relawan' => $request->nama_relawan,
            'jenis_psks'   => $request->jenis_psks,
            'kecamatan'    => $request->kecamatan,
            'no_hp'        => $request->no_hp,
            'alamat'       => $request->alamat,
             'foto'         => $relawan->foto,
        ]);

        return redirect()->route('admin.management_pegawai.relawan.index')
    ->with('success', 'data relawan berhasil diupdate');
    }

    public function destroy($id)
    {
        Relawan::findOrFail($id)->delete();

       return redirect()->route('admin.management_pegawai.relawan.index')
    ->with('success', 'data relawan berhasil dihapus');
    }
}