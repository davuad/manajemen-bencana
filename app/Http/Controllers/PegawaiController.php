<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    // menampilkan data pegawai
    public function index()
    {
        $pegawai = Pegawai::all();
        return view('management_pegawai.pegawai.index', compact('pegawai'));
    }

    // menampilkan form tambah pegawai
    public function create()
    {
        return view('management_pegawai.pegawai.create');
    }

    // menyimpan data pegawai
    public function store(Request $request)
    {
        $request->validate([
            'nama_pegawai' => 'required',
            'jabatan' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
            'status_aktif' => 'required',
        ]);

        Pegawai::create([
            'nama_pegawai' => $request->nama_pegawai,
            'jabatan' => $request->jabatan,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
             'status_aktif' => $request->status_aktif,
        ]);

        return redirect('/pegawai')->with('success', 'data berhasil ditambah');
    }

    // menampilkan form edit
    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return view('management_pegawai.pegawai.edit', compact('pegawai'));
    }

    // update data pegawai
    public function update(Request $request, $id)
{
    $request->validate([
        'nama_pegawai' => 'required',
        'jabatan' => 'required',
        'no_hp' => 'required',
        'alamat' => 'required',
        'status_aktif' => 'required',
    ]);

    $pegawai = Pegawai::findOrFail($id);

    $pegawai->update([
        'nama_pegawai' => $request->nama_pegawai,
        'jabatan' => $request->jabatan,
        'no_hp' => $request->no_hp,
        'alamat' => $request->alamat,
        'status_aktif' => $request->status_aktif,
    ]);

    return redirect('/pegawai')->with('success', 'data berhasil diupdate');
}

        

    // hapus data
    public function destroy($id)
    {
        Pegawai::findOrFail($id)->delete();

        return redirect('/pegawai')->with('success', 'data berhasil dihapus');
    }
}