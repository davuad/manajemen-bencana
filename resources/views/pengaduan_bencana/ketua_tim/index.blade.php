@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

{{-- HEADER --}}
<div class="flex justify-between items-center mb-6">

    <div>
        <h2 class="text-xl font-bold">
            MONITORING PENYELESAIAN PENGADUAN
        </h2>

        <p class="text-gray-500 text-sm">
            Monitoring proses penyelesaian pengaduan bencana
        </p>
    </div>

</div>

{{-- FILTER --}}
<form method="GET">

    <div class="flex flex-wrap gap-4 mb-6">

        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Cari pelapor, desa, atau deskripsi..."
               class="flex-1 border rounded-lg px-4 py-2">

        <select name="status"
                class="border rounded-lg px-3 py-2">

            <option value="">
                Semua Status
            </option>

            <option value="DITANGANI"
                {{ request('status') == 'DITANGANI' ? 'selected' : '' }}>
                Ditangani
            </option>

            <option value="SELESAI"
                {{ request('status') == 'SELESAI' ? 'selected' : '' }}>
                Selesai
            </option>

        </select>

        <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg">

            Filter

        </button>

    </div>

</form>

{{-- TABLE --}}
<div class="overflow-x-auto">

    <table class="w-full text-sm">

        <thead class="bg-gray-100">

            <tr>
                <th class="p-3 text-center">ID</th>
                <th class="text-center">Pelapor</th>
                <th class="text-center">Kategori</th>
                <th class="text-center">Lokasi</th>
                <th class="text-center">Status</th>
                <th class="text-center">Tanggal Selesai</th>
                <th class="text-center">Aksi</th>
            </tr>

        </thead>

        <tbody>

            @forelse($data as $d)

            <tr class="border-t hover:bg-gray-50">

                {{-- ID --}}
                <td class="text-center p-3 font-semibold">
                    #{{ $d->id }}
                </td>

                {{-- PELAPOR --}}
                <td class="text-center">
                    {{ $d->user->nama ?? '-' }}
                </td>

                {{-- KATEGORI --}}
                <td class="text-center">
                    {{ $d->kategori->nama_kategori ?? '-' }}
                </td>

                {{-- LOKASI --}}
                <td class="p-3">

                    <div class="font-semibold">
                        {{ $d->desa }}
                    </div>

                    <div class="text-gray-500 text-xs mt-1">
                        {{ \Illuminate\Support\Str::limit($d->deskripsi, 60) }}
                    </div>

                </td>

                {{-- STATUS --}}
                <td class="text-center">

                    @if($d->status_pengaduan == 'SELESAI')

                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                            Selesai
                        </span>

                    @elseif($d->status_pengaduan == 'DITANGANI')

                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                            Ditangani
                        </span>

                    @else

                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">
                            {{ $d->status_pengaduan }}
                        </span>

                    @endif

                </td>

                {{-- TANGGAL SELESAI --}}
                <td class="text-center">

                    @if($d->tanggal_selesai)

                        {{ \Carbon\Carbon::parse($d->tanggal_selesai)->format('d M Y') }}

                    @else

                        -

                    @endif

                </td>

                {{-- AKSI --}}
                <td>

                    <div class="flex justify-center">

                        @if($d->status_pengaduan == 'SELESAI')

                            <span class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-xs font-semibold">

                                Sudah Selesai

                            </span>

                        @else

                            <a href="{{ route('ketua_tim.pengaduan.selesai', $d->id) }}"
                               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-xs">

                                Selesaikan

                            </a>

                        @endif

                    </div>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="7"
                    class="text-center p-6 text-gray-500">

                    Belum ada data pengaduan

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>

</div>

@endsection
