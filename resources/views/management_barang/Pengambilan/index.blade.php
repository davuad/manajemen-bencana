@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">

        <div>
            <h2 class="text-xl font-bold">
                Data Pengambilan
            </h2>

            <p class="text-gray-500 text-sm">
                Kelola data pengambilan barang dari posko
            </p>
        </div>

        <a href="{{ route('admin.management_barang.pengambilan.create') }}"
           class="bg-indigo-700 text-white px-4 py-2 rounded-lg inline-block">

            + Tambah Pengambilan

        </a>

    </div>

    {{-- Search --}}
    <form method="GET"
          action="{{ route('admin.management_barang.pengambilan.index') }}">

        <div class="flex gap-4 mb-6">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari tujuan / petugas / posko"
                   class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">

            <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg">

                Cari

            </button>

        </div>

    </form>

    {{-- Notifikasi --}}
    @if(session('success'))

        <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700">

            {{ session('success') }}

        </div>

    @endif

    {{-- Table --}}
    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">

                <tr>

                    <th class="p-3 text-center">
                        No
                    </th>

                    <th class="p-3 text-left">
                        Bencana
                    </th>

                    <th class="p-3 text-left">
                        Petugas
                    </th>

                    <th class="p-3 text-left">
                        Tanggal
                    </th>

                    <th class="p-3 text-left">
                        Tujuan
                    </th>

                    <th class="p-3 text-left">
                        Posko
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

                @forelse($data as $item)

                <tr class="border-t">

                    {{-- No --}}
                    <td class="p-3 text-center">

                        {{ $loop->iteration }}

                    </td>

                    {{-- Bencana --}}
                    <td class="p-3">

                        {{ $item->bencana->nama_bencana ?? 'Bencana '.$item->bencana_id }}

                    </td>

                    {{-- Petugas --}}
                    <td class="p-3">

                        {{ $item->petugas->nama_petugas ?? '-' }}

                    </td>

                    {{-- Tanggal --}}
                    <td class="p-3">

                        {{ $item->tanggal_pengambilan }}

                    </td>

                    {{-- Tujuan --}}
                    <td class="p-3">

                        {{ $item->tujuan }}

                    </td>

                    {{-- Posko --}}
                    <td class="p-3">

                        {{ $item->posko->nama_posko ?? '-' }}

                    </td>

                    {{-- Status --}}
                    <td class="p-3 text-center">

                        @if($item->status == 'Ditangani')

                            <span class="px-3 py-1 rounded-full bg-yellow-200 text-yellow-800 font-semibold">

                                Ditangani

                            </span>

                        @elseif($item->status == 'Selesai')

                            <span class="px-3 py-1 rounded-full bg-green-200 text-green-800 font-semibold">

                                Selesai

                            </span>

                        @else

                            <span class="px-3 py-1 rounded-full bg-red-200 text-red-700 font-semibold">

                                Dibatalkan

                            </span>

                        @endif

                    </td>

                    {{-- Aksi --}}
                   <td class="p-3 text-center flex justify-center gap-2">
   
    <a href="{{ route('admin.management_barang.pengambilan.show', $item->id) }}" 
       class="text-blue-500 hover:text-blue-700" 
       title="Detail">
        🔍
    </a>

    @if($item->status != 'Dibatalkan')
        <a href="{{ route('admin.management_barang.pengambilan.edit', $item->id) }}" 
           class="text-yellow-500 hover:text-yellow-700" 
           title="Edit">
            ✏️
        </a>
    @else
        <span class="text-gray-300 cursor-not-allowed" title="Tidak bisa edit data yang dibatalkan">✏️</span>
    @endif

    @if($item->status != 'Dibatalkan')
        <form action="{{ route('admin.management_barang.pengambilan.batal', $item->id) }}" 
              method="POST" 
              onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengambilan ini? Stok akan dikembalikan.');"
              class="inline">
            @csrf
            @method('POST') {{-- Sesuaikan dengan method di route kamu, biasanya POST atau PUT --}}
            <button type="submit" class="text-red-500 hover:text-red-700" title="Batalkan Pengambilan">
                ❌
            </button>
        </form>
    @else
        <span class="text-gray-300 cursor-not-allowed" title="Sudah dibatalkan">❌</span>
    @endif
</td>
                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="8"
                        class="text-center p-4 text-gray-500">

                        Data belum ada

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection