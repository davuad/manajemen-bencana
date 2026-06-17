<?php

namespace App\Http\Controllers;

use App\Models\Relawan;
use Illuminate\Http\Request;

class RelawanController extends Controller
{
    public function index()
    {
        $relawan = Relawan::all();
        return view('management_pegawai.relawan.index', compact('relawan'));
    }

    public function create()
    {
        return view('management_pegawai.relawan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_relawan' => 'required|max:100',
            'jenis_psks'   => 'required',
            'kecamatan'    => 'required|max:100',
            'no_hp'        => 'required|max:20',
            'alamat'       => 'required',
        ]);

        Relawan::create([
            'nama_relawan' => $request->nama_relawan,
            'jenis_psks'   => $request->jenis_psks,
            'kecamatan'    => $request->kecamatan,
            'no_hp'        => $request->no_hp,
            'alamat'       => $request->alamat,
        ]);

        return redirect('/relawan')->with('success', 'data relawan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $relawan = Relawan::findOrFail($id);
        return view('management_pegawai.relawan.edit', compact('relawan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_relawan' => 'required|max:100',
            'jenis_psks'   => 'required',
            'kecamatan'    => 'required|max:100',
            'no_hp'        => 'required|max:20',
            'alamat'       => 'required',
        ]);

        $relawan = Relawan::findOrFail($id);

        $relawan->update([
            'nama_relawan' => $request->nama_relawan,
            'jenis_psks'   => $request->jenis_psks,
            'kecamatan'    => $request->kecamatan,
            'no_hp'        => $request->no_hp,
            'alamat'       => $request->alamat,
        ]);

        return redirect('/relawan')->with('success', 'data relawan berhasil diupdate');
    }

    public function destroy($id)
    {
        Relawan::findOrFail($id)->delete();

        return redirect('/relawan')->with('success', 'data relawan berhasil dihapus');
    }
}