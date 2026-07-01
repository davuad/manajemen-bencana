@extends('layouts.app')

@php
    // Definisikan role user dinamis di bagian paling atas agar tidak error undefined variable
    $userRole = auth()->user()->roles->first()->name ?? 'admin';
@endphp

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold text-gray-800">Edit Data Dapur Umum</h2>
        <p class="text-gray-500 text-sm mt-1">
            Perbarui data dapur umum dengan informasi terbaru
        </p>
    </div>

<<<<<<< HEAD
    <div class="bg-white rounded-xl p-5 m-3 mt-5">
        <form action="{{ route('management_posko.dapur_umum.update', $dapur->id) }}" method="POST" class="space-y-6">
=======
    <div class="bg-white rounded-xl p-5 m-3 mt-5 shadow-sm">
        <form action="{{ route('management_posko.dapur_umum.update', ['role' => $userRole, 'dapur_umum' => $dapur->id]) }}" method="POST" class="space-y-6">
>>>>>>> origin/main
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-medium mb-2">Pilih Posko *</label>
                    <select name="posko_id" class="w-full border rounded-lg p-3">
                        <option value="">Pilih Posko</option>
                        @foreach ($posko as $p)
                            <option value="{{ $p->id }}" {{ old('posko_id', $dapur->posko_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_posko }}
                            </option>
                        @endforeach
                    </select>
                    @error('posko_id')
                        <small class="text-red-500 mt-1 block">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium mb-2">Nama Dapur Umum *</label>
                    <input type="text" name="nama_dapur_umum"
                        value="{{ old('nama_dapur_umum', $dapur->nama_dapur_umum) }}" class="w-full border rounded-lg p-3"
                        placeholder="Contoh: Dapur Umum 2">
                    @error('nama_dapur_umum')
                        <small class="text-red-500 mt-1 block">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium mb-2">Kapasitas Warga *</label>
                    <input type="number" name="kapasitas_warga"
                        value="{{ old('kapasitas_warga', $dapur->kapasitas_warga) }}" class="w-full border rounded-lg p-3"
                        placeholder="Masukkan kapasitas maksimal warga">
                    @error('kapasitas_warga')
                        <small class="text-red-500 mt-1 block">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium mb-2">Jumlah Warga Saat Ini *</label>
                    <input type="number" name="jumlah_warga" value="{{ old('jumlah_warga', $dapur->jumlah_warga) }}"
                        class="w-full border rounded-lg p-3" placeholder="Masukkan jumlah warga saat ini">
                    @error('jumlah_warga')
                        <small class="text-red-500 mt-1 block">{{ $message }}</small>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block font-medium mb-2">Penanggung Jawab *</label>
                    <input type="text" name="penanggung_jawab"
                        value="{{ old('penanggung_jawab', $dapur->penanggung_jawab) }}" class="w-full border rounded-lg p-3"
                        placeholder="Masukkan nama penanggung jawab">
                    @error('penanggung_jawab')
                        <small class="text-red-500 mt-1 block">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3">
<<<<<<< HEAD
                <a href="{{ route('management_posko.dapur_umum.index') }}" class="px-4 py-2 bg-gray-300 rounded-lg">
=======
                <a href="{{ route('management_posko.dapur_umum.index', ['role' => $userRole]) }}" 
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg transition duration-200">
>>>>>>> origin/main
                    Batal
                </a>

                <button type="submit" class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition duration-200">
                    Update Data
                </button>
            </div>
        </form>
    </div>
@endsection