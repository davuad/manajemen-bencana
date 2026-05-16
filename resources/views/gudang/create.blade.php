@extends('layouts.app')

@section('content')

<div class="mx-3">
    <h2 class="text-xl font-bold">Tambah Gudang</h2>
</div>

<div class="bg-white rounded-xl p-5 m-3 mt-5">

<form action="{{ route('gudang.store') }}" method="POST">
@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>
        <label>Nama Gudang <span class="text-red-500">*</span></label>
        <input type="text" name="nama_gudang" class="w-full border rounded-lg p-3">
    </div>

    <div>
        <label>Alamat <span class="text-red-500">*</span></label>
        <input type="text" name="alamat" class="w-full border rounded-lg p-3">
    </div>

    <div>
        <label>Kapasitas <span class="text-red-500">*</span></label>
        <input type="number" name="kapasitas" class="w-full border rounded-lg p-3">
    </div>

    <div>
        <label>Keterangan</label>
        <input type="text" name="keterangan" class="w-full border rounded-lg p-3">
    </div>

</div>

<div class="flex justify-end gap-3 mt-6">
    <a href="{{ route('gudang.index') }}" class="bg-gray-300 px-4 py-2 rounded">Batal</a>
    <button class="bg-blue-600 text-white px-6 py-2 rounded">Simpan</button>
</div>

</form>

</div>

@endsection