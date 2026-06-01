@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6 max-w-6xl mx-auto">

    {{-- HEADER --}}
    <div class="mb-6 border-b pb-4">

        <h2 class="text-2xl font-bold text-gray-800">
            Detail Data Pengembalian
        </h2>

        <p class="text-gray-500 text-sm mt-1">
            Informasi lengkap data pengembalian barang
        </p>

    </div>

    {{-- AMBIL DATA GROUP --}}
    @php

        $groupData = \App\Models\Pengembalian::with([
            'pengambilan.barang',
            'pengambilan.bencana',
            'petugas',
            'posko'
        ])

        ->whereDate(
            'tanggal_pengembalian',
            $data->tanggal_pengembalian
        )

        ->where('petugas_id', $data->petugas_id)

        ->where('posko_id', $data->posko_id)

        ->whereHas('pengambilan', function($q) use ($data){

            $q->where('tujuan', $data->pengambilan->tujuan ?? '');

        })

        ->get();

        $first = $groupData->first();

    @endphp

    {{-- INFORMASI --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        {{-- BENCANA --}}
        <div>

            <p class="text-gray-500 text-sm">
                Bencana
            </p>

            <p class="text-lg font-semibold text-gray-800">

                 {{ $first->pengambilan->bencana->nama_bencana ?? 'Bencana '.$first->pengambilan->bencana_id }}
            </p>

        </div>

        {{-- PETUGAS --}}
        <div>

            <p class="text-gray-500 text-sm">
                Petugas
            </p>

            <p class="text-lg font-semibold text-gray-800">

                {{ $first->petugas->nama_petugas ?? '-' }}

            </p>

        </div>

        {{-- POSKO --}}
        <div>

            <p class="text-gray-500 text-sm">
                Posko
            </p>

            <p class="text-lg font-semibold text-gray-800">

                {{ $first->posko->nama_posko ?? '-' }}

            </p>

        </div>

        {{-- TUJUAN --}}
        <div>

            <p class="text-gray-500 text-sm">
                Tujuan
            </p>

            <p class="text-lg font-semibold text-gray-800">

                {{ $first->pengambilan->tujuan ?? '-' }}

            </p>

        </div>

        {{-- TANGGAL --}}
        <div>

            <p class="text-gray-500 text-sm">
                Tanggal Pengembalian
            </p>

            <p class="text-lg font-semibold text-gray-800">

                {{ \Carbon\Carbon::parse($first->tanggal_pengembalian)->format('d-m-Y') }}

            </p>

        </div>

        {{-- STATUS --}}
        <div>

            <p class="text-gray-500 text-sm mb-2">
                Status
            </p>

            @if($first->status == 'Ditangani')

                <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 text-sm font-semibold">

                    Ditangani

                </span>

            @elseif($first->status == 'Selesai')

                <span class="px-4 py-2 rounded-full bg-green-100 text-green-700 text-sm font-semibold">

                    Selesai

                </span>

            @else

                <span class="px-4 py-2 rounded-full bg-red-100 text-red-700 text-sm font-semibold">

                    Dibatalkan

                </span>

            @endif

        </div>

        {{-- KETERANGAN --}}
        <div class="md:col-span-2">

            <p class="text-gray-500 text-sm">
                Keterangan
            </p>

            <p class="text-base font-semibold text-gray-800">

                {{ $first->keterangan ?? '-' }}

            </p>

        </div>

    </div>

    {{-- TABLE BARANG --}}
    <div>

        <h3 class="text-lg font-semibold mb-4">
            Data Barang Pengembalian
        </h3>

        <div class="overflow-x-auto">

            <table class="w-full border text-sm">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="p-3 text-center">
                            No
                        </th>

                        <th class="p-3 text-left">
                            Nama Barang
                        </th>

                        <th class="p-3 text-center">
                            Satuan
                        </th>

                        <th class="p-3 text-center">
                            Jumlah Diambil
                        </th>

                        <th class="p-3 text-center">
                            Jumlah Dikembalikan
                        </th>

                        <th class="p-3 text-center">
                            Stok Saat Ini
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($groupData as $item)

                    <tr class="border-t hover:bg-gray-50">

                        {{-- NO --}}
                        <td class="p-3 text-center">

                            {{ $loop->iteration }}

                        </td>

                        {{-- NAMA BARANG --}}
                        <td class="p-3 font-medium">

                            {{ $item->pengambilan->barang->nama_barang ?? '-' }}

                        </td>

                        {{-- SATUAN --}}
                        <td class="p-3 text-center">

                            {{ $item->pengambilan->barang->satuan ?? '-' }}

                        </td>

                        {{-- JUMLAH DIAMBIL --}}
                        <td class="p-3 text-center">

                            <span class="px-3 py-1 rounded-lg bg-blue-100 text-blue-700 font-semibold">

                                {{ $item->pengambilan->jumlah_ambil ?? 0 }}

                            </span>

                        </td>

                        {{-- JUMLAH KEMBALI --}}
                        <td class="p-3 text-center">

                            <span class="px-3 py-1 rounded-lg bg-green-100 text-green-700 font-semibold">

                                {{ $item->jumlah_kembali ?? 0 }}

                            </span>

                        </td>

                        {{-- STOK --}}
                        <td class="p-3 text-center">

                            <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold">

                                {{ $item->pengambilan->barang->stok ?? 0 }}

                            </span>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="6"
                            class="text-center p-4 text-gray-500">

                            Data barang tidak ditemukan

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- BUTTON --}}
    <div class="mt-8 flex justify-between">

        <a href="{{ route('manajemen_barang.pengembalian.index') }}"
           class="px-5 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">

            Kembali

        </a>

        <div class="flex gap-3">

            <a href="{{ route('manajemen_barang.pengembalian.edit', $data->id) }}"
               class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">

                Edit

            </a>

            <form action="{{ route('manajemen_barang.pengembalian.destroy', $data->id) }}"
                  method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus data ini?')">

                @csrf
                @method('DELETE')

                <button type="submit"
                    class="px-5 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">

                    Hapus

                </button>

            </form>

        </div>

    </div>

</div>

@endsection