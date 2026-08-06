@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">

            <div>

                <h2 class="text-2xl font-bold text-gray-800">
                    Monitoring Penyelesaian Pengaduan
                </h2>

                <p class="text-gray-500 mt-1">
                    Monitoring proses penyelesaian pengaduan bencana.
                </p>

            </div>

        </div>

        {{-- STATISTIK --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

            {{-- Total --}}
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow text-white p-6">

                <p class="text-sm opacity-90">
                    Total Pengaduan
                </p>

                <h2 class="text-4xl font-bold mt-2">

                    {{ $totalPengaduan }}

                </h2>

            </div>

            {{-- Selesai --}}
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow text-white p-6">

                <p class="text-sm opacity-90">
                    Sudah Selesai
                </p>

                <h2 class="text-4xl font-bold mt-2">

                    {{ $totalSelesai }}

                </h2>

            </div>

            {{-- Belum --}}
            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl shadow text-white p-6">

                <p class="text-sm opacity-90">
                    Belum Selesai
                </p>

                <h2 class="text-4xl font-bold mt-2">

                    {{ $totalBelum }}

                </h2>

            </div>

        </div>

        {{-- FILTER --}}
        <form method="GET">

            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-8">

                {{-- Cari --}}
                <div class="md:col-span-2">

                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pelapor, desa..."
                        class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-indigo-200">

                </div>

                {{-- Status --}}
                <div>

                    <select name="status" class="w-full border rounded-lg px-3 py-2">

                        <option value="">
                            Semua Status
                        </option>

                        <option value="DITANGANI" {{ request('status') == 'DITANGANI' ? 'selected' : '' }}>

                            Ditangani

                        </option>

                        <option value="SELESAI" {{ request('status') == 'SELESAI' ? 'selected' : '' }}>

                            Selesai

                        </option>

                    </select>

                </div>

                {{-- Bulan --}}
                <div>

                    <select name="bulan" class="w-full border rounded-lg px-3 py-2">

                        <option value="">
                            Semua Bulan
                        </option>

                        @foreach (range(1, 12) as $bulan)
                            <option value="{{ $bulan }}" {{ request('bulan') == $bulan ? 'selected' : '' }}>

                                {{ DateTime::createFromFormat('!m', $bulan)->format('F') }}

                            </option>
                        @endforeach

                    </select>

                </div>

                {{-- Tahun --}}
                <div>

                    <select name="tahun" class="w-full border rounded-lg px-3 py-2">

                        <option value="">
                            Semua Tahun
                        </option>

                        @for ($i = date('Y'); $i >= 2023; $i--)
                            <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>

                                {{ $i }}

                            </option>
                        @endfor

                    </select>

                </div>

                {{-- Tombol --}}
                <div class="flex gap-2">

                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">

                        Filter

                    </button>

                    <a href="{{ route('ketua_tim.pengaduan.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 px-4 rounded-lg flex items-center">

                        Reset

                    </a>

                </div>

            </div>

        </form>

        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-4 py-3 text-center">
                            ID
                        </th>

                        <th class="px-4 py-3 text-center">
                            Pelapor
                        </th>

                        <th class="px-4 py-3 text-center">
                            Kategori
                        </th>

                        <th class="px-4 py-3 text-center">
                            Lokasi
                        </th>

                        <th class="px-4 py-3 text-center">
                            Status
                        </th>

                        <th class="px-4 py-3 text-center">
                            Tanggal Selesai
                        </th>

                        <th class="px-4 py-3 text-center">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($data as $d)
                        <tr class="hover:bg-gray-50 transition">

                            {{-- ID --}}
                            <td class="px-4 py-4 text-center font-semibold text-gray-700">
                                #{{ $d->id }}
                            </td>

                            {{-- Pelapor --}}
                            <td class="px-4 py-4">

                                <div class="font-semibold text-gray-800">
                                    {{ $d->user->nama ?? '-' }}
                                </div>

                            </td>

                            {{-- Kategori --}}
                            <td class="px-4 py-4">

                                <span
                                    class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">

                                    {{ $d->kategori->nama_kategori ?? '-' }}

                                </span>

                            </td>

                            {{-- Lokasi --}}
                            <td class="px-4 py-4">

                                <div class="font-semibold text-gray-800">
                                    {{ $d->desa }}
                                </div>

                                <div class="text-xs text-gray-500 mt-1">
                                    {{ \Illuminate\Support\Str::limit($d->deskripsi, 70) }}
                                </div>

                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-4 text-center">

                                @if ($d->status_pengaduan == 'SELESAI')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">

                                        ✓ Selesai

                                    </span>
                                @elseif($d->status_pengaduan == 'DITANGANI')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">

                                        ⏳ Ditangani

                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">

                                        {{ $d->status_pengaduan }}

                                    </span>
                                @endif

                            </td>

                            {{-- Tanggal --}}
                            <td class="px-4 py-4 text-center">

                                @if ($d->tanggal_selesai)
                                    <div class="text-sm text-gray-700">

                                        {{ \Carbon\Carbon::parse($d->tanggal_selesai)->format('d M Y') }}

                                    </div>
                                @else
                                    <span class="text-gray-400">

                                        -

                                    </span>
                                @endif

                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-4">

                                <div class="flex justify-center">

                                    @if ($d->status_pengaduan == 'SELESAI')
                                        <span
                                            class="inline-flex px-4 py-2 rounded-lg bg-green-100 text-green-700 text-xs font-semibold">

                                            Sudah Selesai

                                        </span>
                                    @else
                                        <a href="{{ route('ketua_tim.pengaduan.selesai', $d->id) }}"
                                            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition">

                                            Selesaikan

                                        </a>
                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="text-center py-12">

                                <div class="flex flex-col items-center">

                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                        stroke-width="1.5" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />

                                    </svg>

                                    <p class="text-gray-500">

                                        Belum ada data pengaduan.

                                    </p>

                                </div>

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>
@endsection
