@extends('layouts.app')

@section('content')
<div class="bg-slate-200 min-h-screen p-4 md:p-6">

    {{-- Header --}}
    <div class="bg-white px-6 py-4 flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-black">Detail Anak Terpisah</h1>
            <p class="text-sm text-gray-500">Informasi lengkap data anak</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.anak_terpisah.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">
                Kembali
            </a>

            <a href="{{ route('admin.anak_terpisah.edit', $anak->id) }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg">
                Edit
            </a>
        </div>
    </div>

    {{-- Content --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- FOTO --}}
        <div class="bg-white rounded-xl shadow p-6 text-center">

            @if($anak->foto_anak)
                <img src="{{ asset('storage/'.$anak->foto_anak) }}"
                     class="w-48 h-56 object-cover mx-auto rounded-lg">
            @else
                <div class="w-48 h-56 mx-auto flex items-center justify-center bg-gray-100 rounded-lg text-gray-400">
                    Tidak ada foto
                </div>
            @endif

            <div class="mt-4 text-left text-sm space-y-2">
                <p><span class="font-semibold">Nama:</span> {{ $anak->nama_anak }}</p>
                <p><span class="font-semibold">Jenis Kelamin:</span> {{ $anak->jenis_kelamin }}</p>
                <p><span class="font-semibold">Umur:</span> {{ $anak->umur ?? '-' }}</p>
                <p><span class="font-semibold">Nama Bapak:</span> {{ $anak->nama_bapak ?? '-' }}</p>
                <p><span class="font-semibold">Nama Ibu:</span> {{ $anak->nama_ibu ?? '-' }}</p>
            </div>

        </div>

        {{-- DETAIL --}}
        <div class="md:col-span-2 bg-white rounded-xl shadow p-6">

            <h2 class="text-xl font-bold mb-6">{{ $anak->nama_anak }}</h2>

            <div class="space-y-3 text-sm">

                <div>
                    <p class="font-semibold">Nama Anak</p>
                    <p>{{ $anak->nama_anak }}</p>
                </div>

                <div>
                    <p class="font-semibold">Nama Bapak</p>
                    <p>{{ $anak->nama_bapak ?? '-' }}</p>
                </div>

                <div>
                    <p class="font-semibold">Nama Ibu</p>
                    <p>{{ $anak->nama_ibu ?? '-' }}</p>
                </div>

                <div>
                    <p class="font-semibold">Jenis Kelamin</p>
                    <p>{{ $anak->jenis_kelamin }}</p>
                </div>

                <div>
                    <p class="font-semibold">Alamat Asal</p>
                    <p>{{ $anak->alamat_asal ?? '-' }}</p>
                </div>

                <div>
                    <p class="font-semibold">Umur</p>
                    <p>{{ $anak->umur ?? '-' }}</p>
                </div>

                <div>
                    <p class="font-semibold">Tanggal Lahir</p>
                    <p>{{ $anak->tanggal_lahir ?? '-' }}</p>
                </div>

                <div>
                    <p class="font-semibold">Tanggal Ditemukan</p>
                    <p>{{ $anak->tanggal_ditemukan }}</p>
                </div>

                <div>
                    <p class="font-semibold">Lokasi Ditemukan</p>
                    <p>{{ $anak->lokasi_ditemukan }}</p>
                </div>

                <div>
                    <p class="font-semibold">Kontak Keluarga</p>
                    <p>{{ $anak->kontak_keluarga ?? '-' }}</p>
                </div>

                <div>
                    <p class="font-semibold">Status Anak</p>
                    <span class="px-3 py-1 rounded-full text-xs
                        {{ $anak->status_anak == 'sudah_dijemput' ? 'bg-green-200 text-green-800' : '' }}
                        {{ $anak->status_anak == 'dalam_proses' ? 'bg-yellow-200 text-yellow-800' : '' }}
                        {{ $anak->status_anak == 'belum_dijemput' ? 'bg-red-200 text-red-700' : '' }}
                    ">
                        {{ ucfirst(str_replace('_', ' ', $anak->status_anak)) }}
                    </span>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection