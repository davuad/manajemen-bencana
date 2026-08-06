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
    <div class="bg-white rounded-xl shadow p-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold">Penjemputan Anak</h2>
            <p class="text-gray-500 text-sm">
                Kelola data penjemputan anak
            </p>
        </div>
    </div>

    {{-- SEARCH & FILTER --}}
    <form method="GET" class="mb-4 flex flex-wrap gap-2">

        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari nama anak..."
            class="border rounded-lg p-3 flex-1 min-w-[250px]"
        >

        <select
            name="status"
            class="border rounded-lg p-3"
        >
            <option value="">Semua Status</option>

            <option value="menunggu"
                {{ request('status') == 'menunggu' ? 'selected' : '' }}>
                Menunggu
            </option>

            <option value="valid"
                {{ request('status') == 'valid' ? 'selected' : '' }}>
                Sudah Dijemput
            </option>

        </select>

        <button
            type="submit"
            class="bg-blue-500 text-white px-4 py-3 rounded-lg">
            Cari
        </button>

        <a href="{{ route($prefix.'.penjemputan.index') }}"
           class="bg-gray-300 px-4 py-3 rounded-lg">
            Reset
        </a>

    </form>

    @if(request('search') || request('status'))
        <p class="text-sm text-gray-500 mb-3">
            Menampilkan hasil

            @if(request('search'))
                untuk kata kunci
                <span class="font-semibold">
                    "{{ request('search') }}"
                </span>
            @endif

            @if(request('status'))
                dengan status
                <span class="font-semibold">
                    {{ request('status') }}
                </span>
            @endif
        </p>
    @endif

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-center">No</th>
                    <th>Foto</th>
                    <th>Nama Anak</th>
                    <th>Bencana</th>
                    <th>Umur</th>
                    <th>Lokasi</th>
                    <th>Penjemput</th>
                    <th>Petugas</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($data as $index => $item)

                <tr class="border-t">

                    <td class="p-2 text-center">
                        {{ $index + 1 }}
                    </td>

                    <td class="p-2">
                        @if($item->foto_anak)
                            <img
                                src="{{ asset('storage/'.$item->foto_anak) }}"
                                class="w-16 h-16 object-cover rounded">
                        @endif
                    </td>

                    <td class="p-2">
                        {{ $item->nama_anak }}
                    </td>

                    <td class="p-2">
                        {{ $item->bencana->nama_bencana ?? '-' }}
                    </td>

                    <td class="p-2">
                        {{ $item->umur ?? '-' }}
                    </td>

                    <td class="p-2">
                        {{ $item->lokasi_ditemukan }}
                    </td>

                    <td class="p-2">
                        {{ $item->penjemputan->penjemput->nama_penjemput ?? '-' }}
                    </td>

                    <td class="p-2">
                        {{ $item->penjemputan->petugas->nama_petugas ?? '-' }}
                    </td>

                    <td class="p-2">
    {{ $item->penjemputan->status_verifikasi ?? 'NULL' }}
</td>

                    <td class="p-2 text-center">

    @if(!$item->penjemputan)

    @unless(auth()->user()->hasRole('relawan'))
        <a href="{{ route($prefix.'.penjemputan.jemput', $item->id) }}"
           class="text-blue-500">
            ✏️
        </a>
    @endunless

@else

    <div class="flex justify-center">
        <a href="{{ route($prefix.'.penjemputan.show', $item->penjemputan->id) }}"
           class="text-gray-700">
            👁️
        </a>
    </div>

@endif

</td>

                </tr>

            @empty

                <tr>
                    <td colspan="9" class="text-center p-4">
                        Belum ada data
                    </td>
                </tr>

            @endforelse
            </tbody>

        </table>
    </div>

</div>
</div>
@endsection