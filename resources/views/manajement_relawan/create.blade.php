@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6 max-w-2xl">

    <div class="mb-6">
        <h2 class="text-xl font-bold">Tambah Relawan</h2>
        <p class="text-gray-500 text-sm">
            Tambahkan data relawan PSKS
        </p>
    </div>

    <form action="{{ url('/relawan') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Nama Relawan -->
        <div>
            <label class="block text-sm font-medium mb-1">Nama Relawan</label>
            <input type="text" name="nama_relawan"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200"
                value="{{ old('nama_relawan') }}">

            @error('nama_relawan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Jenis PSKS -->
        <div>
            <label class="block text-sm font-medium mb-1">Jenis PSKS</label>
            <select name="jenis_psks"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200">
                <option value="">-- pilih jenis psks --</option>
                <option value="pordam" {{ old('jenis_psks') == 'pordam' ? 'selected' : '' }}>pordam</option>
                <option value="karang taruna" {{ old('jenis_psks') == 'karang taruna' ? 'selected' : '' }}>karang taruna</option>
                <option value="tagana" {{ old('jenis_psks') == 'tagana' ? 'selected' : '' }}>tagana</option>
                <option value="tsks" {{ old('jenis_psks') == 'tsks' ? 'selected' : '' }}>tsks</option>
            </select>

            @error('jenis_psks')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Kecamatan -->
        <div>
            <label class="block text-sm font-medium mb-1">Kecamatan</label>
            <input type="text" name="kecamatan"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200"
                value="{{ old('kecamatan') }}">

            @error('kecamatan')
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

            <a href="{{ url('/relawan') }}"
               class="bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300">
                Kembali
            </a>
        </div>

    </form>

</div>

@endsection