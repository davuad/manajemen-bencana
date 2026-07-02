<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    public function index(Request $request)
    {
        $query = Gudang::query();

        // 🔍 SEARCH
        if ($request->search) {
            $query->where('nama_gudang', 'like', '%' . $request->search . '%');
        }

        $gudang = $query->latest()->paginate(5);

        return view('gudang.index', compact('gudang'));
    }

    public function create()
    {
        return view('gudang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_gudang' => 'required',
            'alamat' => 'required',
            'kapasitas' => 'required|integer'
        ]);

        Gudang::create($request->all());

        return redirect()->route('admin.gudang.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $gudang = Gudang::findOrFail($id);
        return view('gudang.edit', compact('gudang'));
    }

    public function update(Request $request, $id)
    {
        $gudang = Gudang::findOrFail($id);

        $gudang->update($request->all());

        return redirect()->route('admin.gudang.index');
    }

    public function destroy($id)
    {
        Gudang::findOrFail($id)->delete();

        return redirect()->route('admin.gudang.index');
    }
}