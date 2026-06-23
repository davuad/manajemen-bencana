@extends('layouts.app')

@section('content')

<div class="mx-3">
    <h2 class="text-xl font-bold">Edit Data Pegawai</h2>
    <p class="text-gray-500 text-sm">
        Perbarui data pegawai dengan informasi terbaru
    </p>
</div>

<div class="bg-white rounded-xl p-5 m-3 mt-5">
   <form action="{{ route('admin.management_pegawai.pegawai.update', $pegawai->id_pegawai) }}" method="POST">
    @csrf
    @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Nama Pegawai -->
            <div>
                <label class="block font-medium">Nama Pegawai *</label>
                <input type="text" name="nama_pegawai"
                    value="{{ old('nama_pegawai', $pegawai->nama_pegawai) }}"
                    class="w-full border rounded-lg p-3"
                    placeholder="Masukkan nama pegawai">

                @error('nama_pegawai')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jabatan -->
            <div>
                <label class="block font-medium">Jabatan *</label>
                <input type="text" name="jabatan"
                    value="{{ old('jabatan', $pegawai->jabatan) }}"
                    class="w-full border rounded-lg p-3"
                    placeholder="Masukkan jabatan">

                @error('jabatan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- No HP -->
            <div>
                <label class="block font-medium">No HP *</label>
                <input type="text" name="no_hp"
                    value="{{ old('no_hp', $pegawai->no_hp) }}"
                    class="w-full border rounded-lg p-3"
                    placeholder="Masukkan nomor HP">

                @error('no_hp')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Alamat -->
            <div>
                <label class="block font-medium">Alamat *</label>
                <textarea name="alamat" rows="3"
                    class="w-full border rounded-lg p-3"
                    placeholder="Masukkan alamat pegawai">{{ old('alamat', $pegawai->alamat) }}</textarea>

                @error('alamat')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Pegawai -->
            <div>
                <label class="block font-medium">Status Pegawai *</label>

                <select name="status_aktif"
                    class="w-full border rounded-lg p-3">

                    <option value="1"
                        {{ old('status_aktif', $pegawai->status_aktif) == 1 ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option value="0"
                        {{ old('status_aktif', $pegawai->status_aktif) == 0 ? 'selected' : '' }}>
                        Tidak Aktif
                    </option>

                </select>

                @error('status_aktif')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="flex justify-end gap-3">
             <a href="{{ route('admin.management_pegawai.pegawai.index') }}"
                class="px-4 py-2 bg-gray-300 rounded-lg">
                Batal
            </a>

            <button type="submit"
                class="px-6 py-2 bg-yellow-500 text-white rounded-lg">
                Update Data
            </button>
        </div>

    </form>
</div>

@endsection