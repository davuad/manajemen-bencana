@extends('layouts.app')

@php
    // Definisikan role user dinamis di bagian paling atas agar tidak error undefined variable
    $userRole = auth()->user()->roles->first()->name ?? 'admin';
@endphp

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold text-gray-800">Tambah Data Posko</h2>
        <p class="text-gray-500 text-sm mt-1">
            Lengkapi data di bawah ini secara akurat untuk mempermudah koordinasi bantuan logistik
        </p>
    </div>

    <div class="bg-white rounded-xl p-5 m-3 mt-5 shadow-sm">
        <form action="{{ route('management_posko.posko.store', ['role' => $userRole]) }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block font-medium mb-2">Nama Posko *</label>
                    <input type="text" name="nama_posko" value="{{ old('nama_posko') }}" class="w-full border rounded-lg p-3"
                        placeholder="Contoh: Posko Balai Desa Suka Maju">
                    @error('nama_posko')
                        <small class="text-red-500 mt-1 block">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium mb-2">Tanggal Dibuat *</label>
                    <input type="date" name="tanggal_dibuat" value="{{ old('tanggal_dibuat') }}" class="w-full border rounded-lg p-3">
                    @error('tanggal_dibuat')
                        <small class="text-red-500 mt-1 block">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium mb-2">Desa Terdampak *</label>
                    <select name="desa_id" class="w-full border rounded-lg p-3">
                        <option value="">Pilih Desa</option>
                        @foreach ($desa as $d)
                            <option value="{{ $d->id }}" {{ old('desa_id') == $d->id ? 'selected' : '' }}>
                                {{ $d->nama_desa }}
                            </option>
                        @endforeach
                    </select>
                    @error('desa_id')
                        <small class="text-red-500 mt-1 block">{{ $message }}</small>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block font-medium mb-2">Nama Bencana</label>
                    <select name="bencana_id" class="w-full border rounded-lg p-3">
                        <option value="">Pilih Nama Bencana</option>
                        @foreach ($bencana as $b)
                            <option value="{{ $b->id }}" {{ old('bencana_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->nama_bencana }}
                            </option>
                        @endforeach
                    </select>
                    @error('bencana_id')
                        <small class="text-red-500 mt-1 block">{{ $message }}</small>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block font-medium mb-2">Tautkan ke Laporan Bencana *</label>
                    <select name="pengaduan_bencana_id" class="w-full border rounded-lg p-3">
                        <option value="">Pilih Laporan</option>
                        @foreach ($pengaduan as $p)
                            <option value="{{ $p->id }}" {{ old('pengaduan_bencana_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->deskripsi }}
                            </option>
                        @endforeach
                    </select>
                    @error('pengaduan_bencana_id')
                        <small class="text-red-500 mt-1 block">{{ $message }}</small>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block font-medium mb-2">Alamat Lengkap / Lokasi *</label>
                    <textarea name="lokasi" class="w-full border rounded-lg p-3" rows="3" placeholder="Masukkan detail alamat...">{{ old('lokasi') }}</textarea>
                    @error('lokasi')
                        <small class="text-red-500 mt-1 block">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('management_posko.posko.index', ['role' => $userRole]) }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg transition duration-200">
                    Batal
                </a>

                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200">
                    Simpan Data Posko
                </button>
            </div>
        </form>
    </div>
@endsection