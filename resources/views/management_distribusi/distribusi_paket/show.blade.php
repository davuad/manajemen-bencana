@extends('layouts.app')

@section('content')
    @php
        $routePrefix = request()->segment(1);
    @endphp
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold">Detail Distribusi Bantuan</h2>
                <p class="text-sm text-gray-500">
                    Informasi lengkap distribusi paket bantuan.
                </p>
            </div>

            <div class="flex justify-end gap-3 mt-6">

                {{-- Kembali --}}
                <a href="{{ route($routePrefix . '.management_distribusi.distribusi_paket.index') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg">
                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                    Kembali
                </a>

                {{-- Sudah Disalurkan --}}
                @if ($distribusi->status_distribusi == 'Proses Penyaluran')
                    @hasanyrole('admin|petugas')
                        <form
                            action="{{ route($routePrefix . '.management_distribusi.distribusi_paket.selesai', $distribusi->id) }}"
                            method="POST">

                            @csrf
                            @method('PATCH')

                            <button
                                class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                                <x-heroicon-o-check class="w-4 h-4" />
                                Selesai
                            </button>

                        </form>
                    @endhasanyrole
                @endif

            </div>

        </div>

        {{-- DATA UTAMA --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- DATA WARGA --}}
            <div class="bg-white rounded-xl shadow p-5">

                {{-- HEADER CARD --}}
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <x-heroicon-o-user class="w-5 h-5 text-blue-600" />
                    </div>
                    <h2 class="text-base font-semibold text-gray-800">Data Warga</h2>
                </div>

                {{-- ISI --}}
                <div class="bg-gray-50 rounded-lg p-4 space-y-2 text-sm">

                    <div class="flex justify-between">
                        <span class="text-gray-500">No. KK</span>
                        <span class="font-medium">{{ $distribusi->wargaTerdampak->no_kk ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Nama</span>
                        <span class="font-medium">{{ $distribusi->wargaTerdampak->nama_kepala_keluarga ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Desa</span>
                        <span class="font-medium">{{ $distribusi->wargaTerdampak->desa->nama_desa ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Bencana</span>
                        <span class="font-medium">{{ $distribusi->wargaTerdampak->bencana->nama_bencana ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Jumlah Anggota</span>
                        <span class="font-medium">{{ $distribusi->wargaTerdampak->jumlah_anggota ?? '-' }}</span>
                    </div>

                </div>
            </div>

            {{-- DATA DISTRIBUSI --}}
            <div class="bg-white rounded-xl shadow p-5">

                {{-- HEADER CARD --}}
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-indigo-100 p-2 rounded-lg">
                        <x-heroicon-o-archive-box class="w-5 h-5 text-indigo-600" />
                    </div>
                    <h2 class="text-base font-semibold text-gray-800">Data Distribusi</h2>
                </div>

                {{-- ISI --}}
                <div class="bg-gray-50 rounded-lg p-4 space-y-2 text-sm">

                    <div class="flex justify-between">
                        <span class="text-gray-500">Paket Bantuan</span>
                        <span class="font-medium">{{ $distribusi->paketBantuan->nama_paket ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Jumlah Paket</span>
                        <span class="font-medium">{{ $distribusi->jumlah_paket }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal</span>
                        <span class="font-medium">
                            {{ $distribusi->tanggal_distribusi ? \Carbon\Carbon::parse($distribusi->tanggal_distribusi)->format('d-m-Y') : '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Petugas</span>
                        <span class="font-medium">{{ $distribusi->petugas->nama_petugas ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Status</span>

                        @if ($distribusi->status_distribusi == 'Proses Penyaluran')
                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                {{ $distribusi->status_distribusi }}
                            </span>
                        @elseif ($distribusi->status_distribusi == 'Sudah disalurkan')
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                {{ $distribusi->status_distribusi }}
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">
                                {{ $distribusi->status_distribusi }}
                            </span>
                        @endif
                    </div>

                </div>
            </div>

        </div>

        {{-- DETAIL BARANG --}}
        <div class="bg-white rounded-xl shadow p-5">

            {{-- HEADER --}}
            <div class="flex items-center gap-3  mb-4">
                <div class="bg-green-100 p-2 rounded-lg">
                    <x-heroicon-o-cube class="w-5 h-5 text-green-600" />
                </div>

                <div>
                    <h2 class="text-base font-semibold text-gray-800">Detail Barang Paket</h2>
                    <p class="text-xs text-gray-500">Isi barang dalam 1 paket bantuan</p>
                </div>
            </div>

            {{-- TABEL --}}
            <div class="overflow-x-auto rounded-lg">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="p-3 text-center">No</th>
                            <th class="p-3 text-center">Nama Barang</th>
                            <th class="p-3 text-center">Jumlah</th>
                            <th class="p-3 text-center">Satuan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($distribusi->paketBantuan->detailPaket ?? [] as $detail)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 text-center">{{ $loop->iteration }}</td>

                                <td class="p-3 text-center">
                                    {{ $detail->barang->nama_barang ?? '-' }}
                                </td>

                                <td class="p-3 text-center">
                                    {{ $detail->jumlah ?? ($detail->jumlah_barang ?? '-') }}
                                </td>

                                <td class="p-3 text-center">
                                    {{ $detail->barang->satuan ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">
                                    Tidak ada data barang
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
