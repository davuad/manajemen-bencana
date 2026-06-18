@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">

        <div>
            <h2 class="text-xl font-bold">
                VERIFIKASI PENGADUAN
            </h2>

            <p class="text-gray-500 text-sm">
                Data pengaduan yang memerlukan verifikasi Kabid
            </p>
        </div>

    </div>

    {{-- FILTER --}}
    <form method="GET">

        <div class="flex flex-wrap gap-4 mb-6">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari pengaduan..."
                   class="flex-1 border rounded-lg px-4 py-2">

            <select name="status"
                    class="border rounded-lg px-3 py-2">

                <option value="">
                    Semua Status
                </option>

                <option value="BELUM_DITANGANI"
                    {{ request('status') == 'BELUM_DITANGANI' ? 'selected' : '' }}>
                    Belum Ditangani
                </option>

                <option value="DITANGANI"
                    {{ request('status') == 'DITANGANI' ? 'selected' : '' }}>
                    Ditangani
                </option>

                <option value="SELESAI"
                    {{ request('status') == 'SELESAI' ? 'selected' : '' }}>
                    Selesai
                </option>

                <option value="TIDAK_DIREKOMENDASIKAN"
                    {{ request('status') == 'TIDAK_DIREKOMENDASIKAN' ? 'selected' : '' }}>
                    Ditolak
                </option>

            </select>

            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
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
                    <th class="text-center">Desa</th>
                    <th class="text-center">Foto</th>
                    <th class="text-center">Status</th>
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

                    {{-- DESA --}}
                    <td>
                        <div class="font-semibold">
                            {{ $d->desa }}
                        </div>

                        <div class="text-gray-500 text-xs mt-1">
                            {{ Str::limit($d->deskripsi, 50) }}
                        </div>
                    </td>

                    {{-- FOTO --}}
                    <td class="text-center">

                        @if($d->foto->count() > 0)

                            <span class="text-indigo-600">
                                {{ $d->foto->count() }} Foto
                            </span>

                        @else

                            <span class="text-gray-400">
                                Tidak ada
                            </span>

                        @endif

                    </td>

                    {{-- STATUS --}}
                    <td class="text-center">

                        @if($d->status_pengaduan == 'BELUM_DITANGANI')

                            <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs">
                                Belum Ditangani
                            </span>

                        @elseif($d->status_pengaduan == 'DITANGANI')

                            <span class="bg-yellow-200 text-yellow-800 px-3 py-1 rounded-full text-xs">
                                Ditangani
                            </span>

                        @elseif($d->status_pengaduan == 'SELESAI')

                            <span class="bg-green-200 text-green-800 px-3 py-1 rounded-full text-xs">
                                Selesai
                            </span>

                        @else

                            <span class="bg-red-200 text-red-800 px-3 py-1 rounded-full text-xs">
                                Ditolak
                            </span>

                        @endif

                    </td>

                    {{-- AKSI --}}
                    <td class="py-3">

                        <div class="flex justify-center gap-2">

                            @if($d->status_pengaduan != 'SELESAI')

                            <a href="{{ route('kabid.pengaduan.verifikasi', $d->id) }}"
                               class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-xs">

                                Verifikasi

                            </a>

                            @endif

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="7"
                        class="text-center p-5 text-gray-500">

                        Belum ada data pengaduan

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection