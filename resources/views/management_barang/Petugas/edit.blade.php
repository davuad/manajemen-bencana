@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded-lg shadow">

    <h2 class="text-2xl font-bold mb-6">Edit Petugas</h2>

    {{-- Error Validation --}}
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('management_barang.petugas.update', $petugas->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Nama Petugas --}}
        <div class="mb-4">
            <label class="block font-medium mb-1">Nama Petugas</label>
            <input type="text" name="nama_petugas"
                value="{{ old('nama_petugas', $petugas->nama_petugas) }}"
                class="w-full border rounded px-3 py-2">
        </div>

        {{-- Jabatan --}}
        <div class="mb-4">
            <label class="block font-medium mb-1">Jabatan</label>
            <select name="jabatan" class="w-full border rounded px-3 py-2">
                <option value="">-- Pilih Jabatan --</option>
                <option value="Admin" {{ old('jabatan', $petugas->jabatan) == 'Admin' ? 'selected' : '' }}>Admin</option>
                <option value="Relawan" {{ old('jabatan', $petugas->jabatan) == 'Relawan' ? 'selected' : '' }}>Relawan</option>
                <option value="Koordinator" {{ old('jabatan', $petugas->jabatan) == 'Koordinator' ? 'selected' : '' }}>Koordinator</option>
            </select>
        </div>

        {{-- No HP --}}
        <div class="mb-4">
            <label class="block font-medium mb-1">No HP</label>
            <input type="text" name="no_hp"
                value="{{ old('no_hp', $petugas->no_hp) }}"
                class="w-full border rounded px-3 py-2">
        </div>

        {{-- Tahun --}}
        <div class="mb-4">
            <label class="block font-medium mb-1">Tahun</label>
            <input type="number" name="tahun"
                value="{{ old('tahun', $petugas->tahun) }}"
                class="w-full border rounded px-3 py-2">
        </div>

        {{-- Status --}}
        <div class="mb-4">
            <label class="block font-medium mb-1">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2">
                <option value="">-- Pilih Status --</option>
                <option value="aktif" {{ old('status', $petugas->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ old('status', $petugas->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        {{-- Nama Posko --}}
        <div class="mb-6">
            <label class="block font-medium mb-1">Nama Posko</label>
            <select name="posko_id" class="w-full border rounded px-3 py-2">
                <option value="">-- Pilih Posko --</option>

                @foreach($posko as $p)
                    <option value="{{ $p->id }}"
                        {{ old('posko_id', $petugas->posko_id) == $p->id ? 'selected' : '' }}>
                        {{ $p->nama_posko }}
                    </option>
                @endforeach

            </select>
        </div>

        {{-- Tombol --}}
        <div class="flex justify-between">
            <a href="{{ route('management_barang.petugas.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded">
                Kembali
            </a>

            <button type="submit"
                class="bg-yellow-500 text-white px-4 py-2 rounded">
                Update
            </button>
        </div>

    </form>
</div>
@endsection