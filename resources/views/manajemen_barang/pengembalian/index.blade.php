@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">

        <div>

            <h2 class="text-xl font-bold">
                Data Pengembalian
            </h2>

            <p class="text-gray-500 text-sm">
                Kelola data pengembalian barang ke posko
            </p>

        </div>

        <a href="{{ route('manajemen_barang.pengembalian.create') }}"
           class="bg-indigo-700 text-white px-4 py-2 rounded-lg inline-block">

            + Tambah Pengembalian

        </a>

    </div>

    {{-- SEARCH --}}
    <form method="GET"
          action="{{ route('manajemen_barang.pengembalian.index') }}">

        <div class="flex gap-4 mb-6">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari tujuan / bencana / barang"
                   class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">

            <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg">

                Cari

            </button>

        </div>

    </form>

    {{-- FILTER STATUS --}}
    <div class="flex gap-3 mb-6 flex-wrap">

        <a href="{{ route('manajemen_barang.pengembalian.index') }}"
           class="px-4 py-2 rounded-lg
           {{ request('status') == '' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">

            Semua

        </a>

        <a href="{{ route('manajemen_barang.pengembalian.index', ['status' => 'Ditangani']) }}"
           class="px-4 py-2 rounded-lg
           {{ request('status') == 'Ditangani'
                ? 'bg-yellow-500 text-white'
                : 'bg-yellow-100 text-yellow-700' }}">

            Ditangani

        </a>

        <a href="{{ route('manajemen_barang.pengembalian.index', ['status' => 'Selesai']) }}"
           class="px-4 py-2 rounded-lg
           {{ request('status') == 'Selesai'
                ? 'bg-green-600 text-white'
                : 'bg-green-100 text-green-700' }}">

            Selesai

        </a>

        <a href="{{ route('manajemen_barang.pengembalian.index', ['status' => 'Dibatalkan']) }}"
           class="px-4 py-2 rounded-lg
           {{ request('status') == 'Dibatalkan'
                ? 'bg-red-600 text-white'
                : 'bg-red-100 text-red-700' }}">

            Dibatalkan

        </a>

    </div>

    {{-- ALERT --}}
    @if(session('success'))

        <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700">

            {{ session('success') }}

        </div>

    @endif

    @if(session('error'))

        <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700">

            {{ session('error') }}

        </div>

    @endif

    {{-- TABLE --}}
    <div class="overflow-x-auto">

        <table class="w-full text-sm border">

            <thead class="bg-gray-100">

                <tr>

                    <th class="p-3 text-center">
                        No
                    </th>

                    <th class="p-3 text-left">
                        Bencana
                    </th>

                    <th class="p-3 text-left">
                        Tujuan
                    </th>

                    <th class="p-3 text-left">
                        Petugas
                    </th>

                    <th class="p-3 text-left">
                        Posko
                    </th>

                    <th class="p-3 text-center">
                        Tanggal
                    </th>

                    <th class="p-3 text-center">
                        Total Barang
                    </th>

                    <th class="p-3 text-center">
                        Total Kembali
                    </th>

                    <th class="p-3 text-center">
                        Status
                    </th>

                    <th class="p-3 text-center">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody>

            @php

            $grouped = $data->groupBy(function($item){

                return
                    optional($item->pengambilan)->bencana_id .
                    '-' .
                    $item->posko_id .
                    '-' .
                    $item->tanggal_pengembalian .
                    '-' .
                    optional($item->pengambilan)->tujuan;

            });

            @endphp

            @forelse($grouped as $group)

                @php

                    $first = $group->first();

                    $totalBarang =
                        $group->count();

                    $totalKembali =
                        $group->sum('jumlah_kembali');

                @endphp

                <tr class="border-t hover:bg-gray-50">

                    {{-- NO --}}
                    <td class="p-3 text-center">

                        {{ $loop->iteration }}

                    </td>

                    {{-- BENCANA --}}
                    <td class="p-3">

                        <div class="font-semibold">

                            {{ optional(optional($first->pengambilan)->bencana)->nama_bencana
                                ?? 'Bencana '.optional($first->pengambilan)->bencana_id }}

                        </div>

                    </td>

                    {{-- TUJUAN --}}
                    <td class="p-3">

                        <div class="font-medium">

                            {{ optional($first->pengambilan)->tujuan ?? '-' }}

                        </div>

                    </td>

                    {{-- PETUGAS --}}
                    <td class="p-3">

                        {{ optional($first->petugas)->nama_petugas ?? '-' }}

                    </td>

                    {{-- POSKO --}}
                    <td class="p-3">

                        {{ optional($first->posko)->nama_posko ?? '-' }}

                    </td>

                    {{-- TANGGAL --}}
                    <td class="p-3 text-center">

                        {{ \Carbon\Carbon::parse(
                            $first->tanggal_pengembalian
                        )->format('d-m-Y') }}

                    </td>

                    {{-- TOTAL BARANG --}}
                    <td class="p-3 text-center">

                        <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold">

                            {{ $totalBarang }}

                        </span>

                    </td>

                    {{-- TOTAL KEMBALI --}}
                    <td class="p-3 text-center">

                        <span class="px-3 py-1 rounded-lg bg-blue-100 text-blue-700 font-semibold">

                            {{ $totalKembali }}

                        </span>

                    </td>

                    {{-- STATUS --}}
                    <td class="p-3 text-center">

                        @if($first->status == 'Ditangani')

                            <span class="px-3 py-1 rounded-full bg-yellow-200 text-yellow-800 font-semibold">

                                Ditangani

                            </span>

                        @elseif($first->status == 'Selesai')

                            <span class="px-3 py-1 rounded-full bg-green-200 text-green-800 font-semibold">

                                Selesai

                            </span>

                        @else

                            <span class="px-3 py-1 rounded-full bg-red-200 text-red-700 font-semibold">

                                Dibatalkan

                            </span>

                        @endif

                    </td>

                    {{-- AKSI --}}
                    <td class="p-3">

                        <div class="flex justify-center gap-3">

                            {{-- DETAIL --}}
                            <a href="{{ route('manajemen_barang.pengembalian.show', $first->id) }}"
                               class="text-green-600 hover:text-green-800">

                                🔍

                            </a>

                            {{-- EDIT --}}
                            <a href="{{ route('manajemen_barang.pengembalian.edit', $first->id) }}"
                               class="text-blue-600 hover:text-blue-800">

                                ✏️

                            </a>

                            {{-- DELETE --}}
                            <form action="{{ route('manajemen_barang.pengembalian.destroy', $first->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus data ini?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="text-red-600 hover:text-red-800">

                                    🗑️

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="10"
                        class="text-center p-5 text-gray-500">

                        Data pengembalian belum tersedia

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection