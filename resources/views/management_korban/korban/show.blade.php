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
        <h2 class="text-xl font-bold">Detail Data Korban</h2>
        <p class="text-gray-500 text-sm">
            Informasi lengkap data korban bencana
        </p>
    </div>

    <div class="bg-white rounded-xl shadow p-6 m-3 mt-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block text-sm font-semibold text-gray-500 mb-1">Nama Korban</label>
                <div class="border rounded-lg p-3 bg-gray-50">
                    {{ $korban->nama }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-500 mb-1">NIK</label>
                <div class="border rounded-lg p-3 bg-gray-50">
                    {{ $korban->nik ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-500 mb-1">Jenis Kelamin</label>
                <div class="border rounded-lg p-3 bg-gray-50">
                    {{ $korban->jenis_kelamin }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-500 mb-1">Umur</label>
                <div class="border rounded-lg p-3 bg-gray-50">
                    {{ $korban->umur }} tahun
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-500 mb-1">Alamat</label>
                <div class="border rounded-lg p-3 bg-gray-50">
                    {{ $korban->alamat }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-500 mb-1">Bencana</label>
                <div class="border rounded-lg p-3 bg-gray-50">
                    {{ $korban->bencana->kategori->nama_kategori ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-500 mb-1">Desa Terdampak</label>
                <div class="border rounded-lg p-3 bg-gray-50">
                    {{ $korban->bencana->desa->nama_desa ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-500 mb-1">Posko</label>
                <div class="border rounded-lg p-3 bg-gray-50">
                    {{ $korban->posko->nama_posko ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-500 mb-1">Lokasi Kejadian</label>
                <div class="border rounded-lg p-3 bg-gray-50">
                    {{ $korban->lokasi_kejadian }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-500 mb-1">Tanggal Kejadian</label>
                <div class="border rounded-lg p-3 bg-gray-50">
                    {{ \Carbon\Carbon::parse($korban->tanggal_kejadian)->format('d-m-Y') }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-500 mb-1">Diinput Oleh</label>
                <div class="border rounded-lg p-3 bg-gray-50">
                    {{ $korban->user->nama ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-500 mb-1">Tanggal Input</label>
                <div class="border rounded-lg p-3 bg-gray-50">
                    {{ $korban->created_at ? $korban->created_at->format('d-m-Y H:i') : '-' }}
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route($routePrefix . '.index') }}" class="px-4 py-2 bg-gray-300 rounded-lg">
                Kembali
            </a>

            <a href="{{ route($routePrefix . '.edit', $korban->id) }}"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg">
                Edit Data
            </a>
        </div>
    </div>
@endsection
