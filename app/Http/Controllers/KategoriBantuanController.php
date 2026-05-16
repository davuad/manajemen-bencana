<?php

namespace App\Http\Controllers;

use App\Models\KategoriBantuan;
use App\Models\Sumber;
use Illuminate\Http\Request;

class KategoriBantuanController extends Controller
{
    public function index(Request $request)
    {
        $query = KategoriBantuan::with('sumber');

        if ($request->search) {
            $query->where('nama_kategori', 'like', '%' . $request->search . '%');
        }

        $kategori = $query->latest()->paginate(5);

        return view('kategori_bantuan.index', compact('kategori'));
    }

    public function create()
    {
        $sumber = Sumber::all();
        return view('kategori_bantuan.create', compact('sumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_sumber' => 'required',
            'nama_kategori' => 'required'
        ]);

        KategoriBantuan::create($request->all());

        return redirect()->route('kategori_bantuan.index');
    }

    public function edit($id)
    {
        $kategori = KategoriBantuan::findOrFail($id);
        $sumber = Sumber::all();

        return view('kategori_bantuan.edit', compact('kategori', 'sumber'));
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriBantuan::findOrFail($id);
        $kategori->update($request->all());

        return redirect()->route('kategori_bantuan.index');
    }

    public function destroy($id)
    {
        KategoriBantuan::findOrFail($id)->delete();

        return redirect()->route('kategori_bantuan.index');
    }
}