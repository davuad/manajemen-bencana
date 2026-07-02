@extends('layouts.app')

@section('content')
@php
    if(auth()->user()->hasRole('petugas')){
        $prefix = 'petugas';
    }elseif(auth()->user()->hasRole('relawan')){
        $prefix = 'relawan';
    }else{
        $prefix = 'admin';
    }
@endphp

<div class="py-6">
    <div class="max-w-7xl mx-auto px-4">

        {{-- Header --}}
        <div class="bg-white rounded-xl shadow p-6 mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Detail Penjemputan Anak</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Informasi lengkap penjemputan anak terpisah
                </p>
            </div>

            <a href="{{ route($prefix.'.penjemputan.index') }}"
               class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm">
                Kembali
            </a>
        </div>

        {{-- GRID ATAS --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- FOTO ANAK --}}
            <div class="bg-white rounded-xl shadow p-6 text-center">

                @if(optional($penjemputan->anak)->foto_anak)
                    <img src="{{ asset('storage/' . $penjemputan->anak->foto_anak) }}"
                         class="w-48 h-56 object-cover mx-auto rounded-lg mb-4">
                @else
                    <div class="w-48 h-56 flex items-center justify-center bg-gray-100 text-gray-400 mx-auto rounded-lg mb-4">
                        Tidak ada foto
                    </div>
                @endif

                <h3 class="text-xl font-bold">
                    {{ optional($penjemputan->anak)->nama_anak ?? '-' }}
                </h3>

                <p class="text-sm text-gray-600 mt-2">
                    {{ optional($penjemputan->anak)->jenis_kelamin }} |
                    {{ optional($penjemputan->anak)->umur }} tahun
                </p>
            </div>

            {{-- DATA ANAK --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
                <h3 class="font-bold text-lg mb-4">Data Anak</h3>

                <div class="space-y-2 text-sm">
                    <p><b>Nama:</b> {{ optional($penjemputan->anak)->nama_anak }}</p>

                    <p><b>Bencana:</b>
                        {{ optional(optional($penjemputan->anak)->bencana)->nama_bencana ?? '-' }}
                    </p>

                    <p><b>Nama Bapak:</b>
                        {{ optional($penjemputan->anak)->nama_bapak ?? '-' }}
                    </p>

                    <p><b>Nama Ibu:</b>
                        {{ optional($penjemputan->anak)->nama_ibu ?? '-' }}
                    </p>

                    <p><b>Alamat Asal:</b>
                        {{ optional($penjemputan->anak)->alamat_asal ?? '-' }}
                    </p>

                    <p><b>Lokasi Ditemukan:</b>
                        {{ optional($penjemputan->anak)->lokasi_ditemukan }}
                    </p>

                    <p><b>Tanggal Ditemukan:</b>
                        {{ optional($penjemputan->anak)->tanggal_ditemukan ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- DATA PENJEMPUT --}}
        <div class="bg-white rounded-xl shadow p-6 mt-6">
            <h3 class="font-bold text-lg mb-4">Data Penjemput</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">

                <div class="space-y-2">
                    <p><b>Nama:</b> {{ optional($penjemputan->penjemput)->nama_penjemput }}</p>
                    <p><b>NIK:</b> {{ optional($penjemputan->penjemput)->nik }}</p>
                    <p><b>Hubungan:</b> {{ optional($penjemputan->penjemput)->hubungan_dengan_anak }}</p>
                    <p><b>No HP:</b> {{ optional($penjemputan->penjemput)->no_hp }}</p>
                    <p><b>Alamat:</b> {{ optional($penjemputan->penjemput)->alamat }}</p>
                </div>

                <div class="space-y-2">
                    <p><b>Petugas:</b> {{ optional($penjemputan->petugas)->nama_petugas ?? '-' }}</p>
                    <p><b>Tanggal Penjemputan:</b>
                        {{ \Carbon\Carbon::parse($penjemputan->tanggal_penjemputan)->format('d M Y') }}
                    </p>

                    <p>
                        <b>Status Verifikasi:</b>
                        @if($penjemputan->status_verifikasi == 'valid')
                            <span class="text-green-600 font-semibold">Valid</span>
                        @elseif($penjemputan->status_verifikasi == 'ditolak')
                            <span class="text-red-600 font-semibold">Ditolak</span>
                        @else
                            <span class="text-yellow-600 font-semibold">Menunggu</span>
                        @endif
                    </p>
                </div>

            </div>

            {{-- CATATAN --}}
            <div class="mt-5">
                <p class="font-semibold mb-2">Catatan:</p>
                <div class="bg-gray-50 border rounded-lg p-4 text-sm">
                    {{ $penjemputan->catatan ?? '-' }}
                </div>
            </div>

            {{-- FILE --}}
            <div class="mt-5 flex gap-3 flex-wrap">

                @if($penjemputan->bukti_dokumen)
                    <a href="{{ asset('storage/' . $penjemputan->bukti_dokumen) }}"
                       target="_blank"
                       class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">
                        Lihat Bukti
                    </a>
                @endif

                @if($penjemputan->berita_acara)
                    <a href="{{ asset('storage/' . $penjemputan->berita_acara) }}"
                       target="_blank"
                       class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm">
                        Lihat Berita Acara
                    </a>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection