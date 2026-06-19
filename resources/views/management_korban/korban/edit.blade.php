@php
    $routePrefix = auth()->user()->hasRole('admin')
        ? 'admin.management_korban.korban'
        : (auth()->user()->hasRole('petugas')
            ? 'petugas.korban'
            : 'relawan.korban');
@endphp

@extends('layouts.app')

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold">Edit Data Korban</h2>
        <p class="text-gray-500 text-sm">
            Perbarui data korban untuk memastikan informasi tetap akurat
        </p>
    </div>

    <div class="bg-white rounded-xl p-5 m-3 mt-5">
        <form action="{{ route($routePrefix . '.update', $korban->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Bencana -->
                <div>
                    <label class="block font-medium">Bencana *</label>
                    <select name="bencana_id" class="w-full border rounded-lg p-3">
                        <option value="">Pilih Bencana</option>
                        @foreach ($bencana as $item)
                            <option value="{{ $item->id }}"
                                {{ old('bencana_id', $korban->bencana_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->kategori->nama_kategori ?? '-' }} - {{ $item->desa->nama_desa ?? '-' }} - {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Posko -->
                <div>
                    <label class="block font-medium">Posko *</label>
                    <select name="posko_id" class="w-full border rounded-lg p-3">
                        <option value="">Pilih Posko</option>
                        @foreach ($posko as $item)
                            <option value="{{ $item->id }}"
                                {{ old('posko_id', $korban->posko_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_posko }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama -->
                <div>
                    <label class="block font-medium">Nama Korban *</label>
                    <input type="text" name="nama" value="{{ old('nama', $korban->nama) }}"
                        class="w-full border rounded-lg p-3" placeholder="Masukkan nama korban">
                </div>

                <!-- NIK -->
                <div>
                    <label class="block font-medium">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik', $korban->nik) }}"
                        class="w-full border rounded-lg p-3" placeholder="Masukkan NIK (opsional)">
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="block font-medium">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" class="w-full border rounded-lg p-3">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-Laki"
                            {{ old('jenis_kelamin', $korban->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }}>
                            Laki-Laki
                        </option>
                        <option value="Perempuan"
                            {{ old('jenis_kelamin', $korban->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                            Perempuan
                        </option>
                    </select>
                </div>

                <!-- Umur -->
                <div>
                    <label class="block font-medium">Umur *</label>
                    <input type="number" name="umur" value="{{ old('umur', $korban->umur) }}"
                        class="w-full border rounded-lg p-3" placeholder="Masukkan umur">
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label class="block font-medium">Alamat *</label>
                    <input type="text" name="alamat" value="{{ old('alamat', $korban->alamat) }}"
                        class="w-full border rounded-lg p-3" placeholder="Masukkan alamat korban">
                </div>

                <!-- Lokasi Kejadian -->
                <div>
                    <label class="block font-medium">Lokasi Kejadian *</label>
                    <input type="text" name="lokasi_kejadian"
                        value="{{ old('lokasi_kejadian', $korban->lokasi_kejadian) }}" class="w-full border rounded-lg p-3"
                        placeholder="Contoh: Dusun A, RT 01">
                </div>

                <!-- Tanggal Kejadian -->
                <div>
                    <label class="block font-medium">Tanggal Kejadian *</label>
                    <input type="date" name="tanggal_kejadian"
                        value="{{ old('tanggal_kejadian', $korban->tanggal_kejadian) }}"
                        class="w-full border rounded-lg p-3">
                </div>

            </div>

            <!-- Error -->
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded">
                    <ul class="list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Button -->
            <div class="flex justify-end gap-3">
                <a href="{{ route($routePrefix . '.index') }}" class="px-4 py-2 bg-gray-300 rounded-lg">
                    Batal
                </a>

                <button type="submit" class="px-6 py-2 bg-yellow-500 text-white rounded-lg">
                    Update Data
                </button>
            </div>

        </form>
    </div>
@endsection
