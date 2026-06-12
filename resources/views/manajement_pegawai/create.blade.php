@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6 max-w-2xl">

    <div class="mb-6">
        <h2 class="text-xl font-bold">Tambah Pegawai</h2>
        <p class="text-gray-500 text-sm">
            Tambahkan data pegawai baru
        </p>
    </div>

    <form action="{{ url('/pegawai') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Nama Pegawai -->
        <div>
            <label class="block text-sm font-medium mb-1">Nama Pegawai</label>
            <input type="text" name="nama_pegawai"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200"
                value="{{ old('nama_pegawai') }}">

            @error('nama_pegawai')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status Pegawai -->
        <div>
            <label class="block text-sm font-medium mb-1">Status Pegawai</label>
            <select name="status_aktif"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200">

                <option value="1" {{ old('status_aktif') == '1' ? 'selected' : '' }}>
                    Aktif
                </option>

                <option value="0" {{ old('status_aktif') == '0' ? 'selected' : '' }}>
                    Tidak Aktif
                </option>

            </select>

            @error('status_aktif')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Jabatan -->
        <div>
            <label class="block text-sm font-medium mb-1">Jabatan</label>
            <input type="text" name="jabatan"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200"
                value="{{ old('jabatan') }}">

            @error('jabatan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- No HP -->
        <div>
            <label class="block text-sm font-medium mb-1">No HP</label>
            <input type="text" name="no_hp"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200"
                value="{{ old('no_hp') }}">

            @error('no_hp')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Alamat -->
        <div>
            <label class="block text-sm font-medium mb-1">Alamat</label>
            <textarea name="alamat" rows="3"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200">{{ old('alamat') }}</textarea>

            @error('alamat')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Button -->
        <div class="flex gap-2 pt-4">
            <button type="submit"
                class="bg-indigo-700 text-white px-4 py-2 rounded-lg hover:bg-indigo-800">
                Simpan
            </button>

            <a href="{{ url('/pegawai') }}"
               class="bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300">
                Kembali
            </a>
        </div>

    </form>

</div>

@endsection