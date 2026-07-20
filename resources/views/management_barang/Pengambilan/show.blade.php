@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-5xl mx-auto">

    {{-- Header --}}
    <div class="mb-6 flex justify-between items-start border-b pb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Detail Data Pengambilan
            </h2>
            <p class="text-gray-500 text-sm mt-1">
                Informasi lengkap data pengambilan barang
            </p>
        </div>

        {{-- Tombol Cetak PDF di Atas --}}
        @if($data->status != 'Dibatalkan')
            <a href="{{ route('admin.management_barang.pengambilan.cetak', $data->id) }}"
               target="_blank"
               class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-2 shadow-sm text-sm font-medium">
                📄 Cetak PDF
            </a>
        @endif
    </div>

    {{-- Informasi Utama --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">

        {{-- Bencana --}}
        <div>
            <label class="font-semibold text-gray-700">
                Bencana
            </label>
            <div class="mt-1">
                {{ $data->bencana->nama_bencana ?? 'Bencana '.$data->bencana_id }}
            </div>
        </div>

        {{-- Petugas --}}
        <div>
            <label class="font-semibold text-gray-700">
                Petugas
            </label>
            <div class="mt-1">
                {{ $data->petugas->nama_petugas ?? '-' }}
            </div>
        </div>

        {{-- Tanggal --}}
        <div>
            <label class="font-semibold text-gray-700">
                Tanggal Pengambilan
            </label>
            <div class="mt-1">
                {{ $data->tanggal_pengambilan }}
            </div>
        </div>

        {{-- Posko --}}
        <div>
            <label class="font-semibold text-gray-700">
                Posko
            </label>
            <div class="mt-1">
                {{ $data->posko->nama_posko ?? '-' }}
            </div>
        </div>

        {{-- Tujuan --}}
        <div class="md:col-span-2">
            <label class="font-semibold text-gray-700">
                Tujuan
            </label>
            <div class="mt-1">
                {{ $data->tujuan }}
            </div>
        </div>

        {{-- Dokumentasi Utama --}}
        <div class="md:col-span-2">
            <label class="font-semibold text-gray-700 block mb-1">
                Dokumentasi Pengambilan
            </label>
            @if($data->gambar)
                <a href="{{ asset('storage/' . $data->gambar) }}" target="_blank" class="inline-block">
                    <img src="{{ asset('storage/' . $data->gambar) }}"
                         alt="Dokumentasi"
                         class="w-48 rounded-lg border shadow-sm hover:opacity-90 transition">
                </a>
            @else
                <p class="text-gray-500 italic text-sm">
                    Belum ada dokumentasi.
                </p>
            @endif
        </div>

        {{-- Status --}}
        <div class="md:col-span-2">
            <label class="font-semibold text-gray-700">
                Status
            </label>
            <div class="mt-3">
                @if($data->status == 'Ditangani')
                    <span class="px-4 py-2 rounded-full bg-yellow-200 text-yellow-800 font-semibold">
                        Ditangani
                    </span>
                @elseif($data->status == 'Selesai')
                    <span class="px-4 py-2 rounded-full bg-green-200 text-green-800 font-semibold">
                        Selesai
                    </span>
                @else
                    <span class="px-4 py-2 rounded-full bg-red-200 text-red-800 font-semibold">
                        Dibatalkan
                    </span>
                @endif
            </div>
        </div>

    </div>

    {{-- Data Barang --}}
    <div>
        <h3 class="text-lg font-semibold mb-4">
            Data Barang Pengambilan
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-center">
                            No
                        </th>
                        <th class="p-3 text-left">
                            Nama Barang
                        </th>
                        <th class="p-3 text-left">
                            Satuan
                        </th>
                        <th class="p-3 text-center">
                            Stok Saat Ini
                        </th>
                        <th class="p-3 text-center">
                            Jumlah Diambil
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangPengambilan as $item)
                    <tr class="border-t">
                        {{-- No --}}
                        <td class="p-3 text-center">
                            {{ $loop->iteration }}
                        </td>

                        {{-- Nama Barang --}}
                        <td class="p-3">
                            {{ $item->barang->nama_barang ?? '-' }}
                        </td>

                        {{-- Satuan --}}
                        <td class="p-3">
                            {{ $item->barang->satuan ?? '-' }}
                        </td>

                        {{-- Stok Saat Ini --}}
                        <td class="p-3 text-center">
                            <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold">
                                {{ $item->barang->stok ?? 0 }}
                            </span>
                        </td>

                        {{-- Jumlah Diambil --}}
                        <td class="p-3 text-center">
                            <span class="px-3 py-1 rounded-lg bg-blue-100 text-blue-700 font-semibold">
                                {{ $item->jumlah_ambil }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr class="border-t">
                        <td colspan="5" class="p-4 text-center text-gray-500 italic">
                            Tidak ada rincian barang.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Button Navigasi Bawah --}}
    <div class="mt-8 flex justify-between">
        <a href="{{ route('admin.management_barang.pengambilan.index') }}"
           class="px-5 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
            Kembali
        </a>

        @if($data->status != 'Dibatalkan')
            <a href="{{ route('admin.management_barang.pengambilan.edit', $data->id) }}"
               class="px-5 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                Edit
            </a>
        @endif
    </div>

</div>
@endsection