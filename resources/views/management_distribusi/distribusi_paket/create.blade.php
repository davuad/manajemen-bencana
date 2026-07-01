@extends('layouts.app')

@section('content')
@php
    $routePrefix = request()->segment(1);
@endphp
<div class="p-6">
    <div class="max-w-5xl mx-auto bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold">Form Distribusi Paket Bantuan</h2>
            <p class="text-sm text-gray-500 mt-1">Lengkapi data distribusi paket untuk warga terdampak.</p>
        </div>

        {{-- Alert Error --}}
        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        {{-- Error Validasi --}}
        @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc pl-5 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route($routePrefix .'.management_distribusi.distribusi_paket.store') }}" method="POST">
            @csrf

            <input type="hidden" name="warga_terdampak_id" value="{{ $warga->id }}">

            {{-- DATA WARGA --}}
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Data Warga Terdampak</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. KK</label>
                        <input type="text" value="{{ $warga->no_kk }}"
                            class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kepala Keluarga</label>
                        <input type="text" value="{{ $warga->nama_kepala_keluarga }}"
                            class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Desa</label>
                        <input type="text" value="{{ $warga->desa->nama_desa ?? '-' }}"
                            class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bencana</label>
                        <input type="text" value="{{ $warga->bencana->nama_bencana ?? '-' }}"
                            class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Anggota</label>
                        <input type="text" value="{{ $warga->jumlah_anggota }}"
                            class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Bantuan</label>
                        <input type="text" value="{{ $warga->jenis_bantuan }}"
                            class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                    </div>
                </div>
            </div>

            {{-- DATA DISTRIBUSI --}}
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Data Distribusi</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Paket Bantuan</label>
                        <select name="paket_bantuan_id" class="w-full border rounded px-3 py-2" required>
                            <option value="">-- Pilih Paket Bantuan --</option>
                            @foreach($paketBantuan as $paket)
                                <option value="{{ $paket->id }}" {{ old('paket_bantuan_id') == $paket->id ? 'selected' : '' }}>
                                    {{ $paket->nama_paket }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Paket</label>
                        <input type="number" name="jumlah_paket" min="1" value="{{ old('jumlah_paket', 1) }}"
                            class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Distribusi</label>
                        <input type="date" name="tanggal_distribusi"
                            value="{{ old('tanggal_distribusi', date('Y-m-d')) }}"
                            class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Petugas Distribusi</label>
                        <select name="petugas_id" class="w-full border rounded px-3 py-2" required>
                            <option value="">-- Pilih Petugas --</option>
                            @foreach($petugas as $item)
                                <option value="{{ $item->id }}" {{ old('petugas_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_petugas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route($routePrefix .'.management_distribusi.distribusi_paket.index') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg">
                    <x-heroicon-o-arrow-left class="w-5 h-5" />Kembali
                </a>

                <button type="submit"
                    class="bg-blue-700 hover:bg-blue-800 text-white px-5 py-2 rounded">
                    Simpan Distribusi
                </button>

            </div>
        </form>
    </div>
</div>
@endsection