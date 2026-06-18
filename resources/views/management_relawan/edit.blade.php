@extends('layouts.app')

@section('content')

<div class="mx-3">
    <h2 class="text-xl font-bold">Edit Data Relawan</h2>
    <p class="text-gray-500 text-sm">
        Perbarui data relawan dengan informasi terbaru
    </p>
</div>

<div class="bg-white rounded-xl p-5 m-3 mt-5">
    <form action="{{ route('admin.management_pegawai.relawan.update', $relawan->id_relawan) }}" method="POST">
    @csrf
    @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block font-medium">Nama Relawan *</label>
                <input type="text" name="nama_relawan"
                    value="{{ old('nama_relawan', $relawan->nama_relawan) }}"
                    class="w-full border rounded-lg p-3"
                    placeholder="Masukkan nama relawan">
                @error('nama_relawan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-medium">Jenis PSKS *</label>
                <select name="jenis_psks" class="w-full border rounded-lg p-3">
                    <option value="">-- pilih jenis psks --</option>
                    <option value="pordam" {{ old('jenis_psks', $relawan->jenis_psks) == 'pordam' ? 'selected' : '' }}>pordam</option>
                    <option value="karang taruna" {{ old('jenis_psks', $relawan->jenis_psks) == 'karang taruna' ? 'selected' : '' }}>karang taruna</option>
                    <option value="tagana" {{ old('jenis_psks', $relawan->jenis_psks) == 'tagana' ? 'selected' : '' }}>tagana</option>
                    <option value="tsks" {{ old('jenis_psks', $relawan->jenis_psks) == 'tsks' ? 'selected' : '' }}>tsks</option>
                </select>
                @error('jenis_psks')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-medium">Kecamatan *</label>
                <input type="text" name="kecamatan"
                    value="{{ old('kecamatan', $relawan->kecamatan) }}"
                    class="w-full border rounded-lg p-3"
                    placeholder="Masukkan kecamatan">
                @error('kecamatan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-medium">No HP *</label>
                <input type="text" name="no_hp"
                    value="{{ old('no_hp', $relawan->no_hp) }}"
                    class="w-full border rounded-lg p-3"
                    placeholder="Masukkan nomor HP">
                @error('no_hp')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block font-medium">Alamat *</label>
                <textarea name="alamat" rows="3"
                    class="w-full border rounded-lg p-3"
                    placeholder="Masukkan alamat relawan">{{ old('alamat', $relawan->alamat) }}</textarea>
                @error('alamat')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.management_pegawai.relawan.index') }}">
                Batal
            </a>

            <button type="submit" class="px-6 py-2 bg-yellow-500 text-white rounded-lg">
                Update Data
            </button>
        </div>
    </form>
</div>

@endsection