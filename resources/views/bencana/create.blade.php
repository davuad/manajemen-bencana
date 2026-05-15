@extends('layouts.app')

@section('content')

<div class="mx-3">
    <h2 class="text-xl font-bold">Tambah Data Bencana</h2>
</div>

<div class="bg-white rounded-xl p-5 m-3 mt-5">

<form action="{{ route('bencana.store') }}" method="POST">
@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>
        <label>Nama Bencana *</label>
        <input type="text" name="nama_bencana" class="w-full border p-3 rounded-lg">
    </div>

    <div>
        <label>Kategori *</label>
        <select name="kategori_id" class="w-full border p-3 rounded-lg">
            @foreach($kategori as $k)
                <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Desa</label>
        <select name="desa_id" class="w-full border p-3 rounded-lg">
            <option value="">-- Pilih Desa --</option>
            @foreach($desa as $d)
                <option value="{{ $d->id }}">{{ $d->nama_desa }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Pengaduan</label>
        <select name="pengaduan_id" class="w-full border p-3 rounded-lg">
            <option value="">-- Pilih Pengaduan --</option>
            @foreach($pengaduan as $p)
                <option value="{{ $p->id }}">{{ $p->deskripsi }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Tanggal *</label>
        <input type="date" name="tanggal" class="w-full border p-3 rounded-lg">
    </div>

    <div>
        <label>Status *</label>
        <select name="status_bencana" class="w-full border p-3 rounded-lg">
            <option value="berlangsung">Berlangsung</option>
            <option value="selesai">Selesai</option>
        </select>
    </div>

    <div>
        <label>Kerusakan *</label>
        <select name="tingkat_kerusakan" class="w-full border p-3 rounded-lg">
            <option value="ringan">Ringan</option>
            <option value="sedang">Sedang</option>
            <option value="parah">Parah</option>
        </select>
    </div>

</div>

<div class="flex justify-end mt-6 gap-3">
    <a href="{{ route('bencana.index') }}" class="bg-gray-300 px-4 py-2 rounded">Batal</a>
    <button class="bg-blue-600 text-white px-6 py-2 rounded">Simpan</button>
</div>

</form>

</div>

@endsection