@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white rounded-xl shadow p-6">

            <div class="flex flex-col md:flex-row md:justify-between md:items-center">

                <div>

                    <h2 class="text-2xl font-bold text-gray-800">

                        Verifikasi Pengaduan

                    </h2>

                    <p class="text-gray-500 mt-1">

                        Kelola dan lakukan verifikasi terhadap seluruh pengaduan
                        bencana yang masuk.

                    </p>

                </div>

            </div>

        </div>

        {{-- STATISTIK --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">

            {{-- Total --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 rounded-xl shadow text-white p-5">

                <div class="flex justify-between items-center">

                    <div>

                        <p class="text-sm opacity-90">

                            Total Pengaduan

                        </p>

                        <h2 class="text-4xl font-bold mt-2">

                            {{ $totalPengaduan }}

                        </h2>

                    </div>

                    <x-heroicon-o-document-text class="w-12 h-12 opacity-30" />

                </div>

            </div>

            {{-- Belum --}}
            <div class="bg-gradient-to-r from-gray-600 to-gray-500 rounded-xl shadow text-white p-5">

                <div class="flex justify-between items-center">

                    <div>

                        <p class="text-sm opacity-90">

                            Belum Ditangani

                        </p>

                        <h2 class="text-4xl font-bold mt-2">

                            {{ $totalBelum }}

                        </h2>

                    </div>

                    <x-heroicon-o-clock class="w-12 h-12 opacity-30" />

                </div>

            </div>

            {{-- Ditangani --}}
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl shadow text-white p-5">

                <div class="flex justify-between items-center">

                    <div>

                        <p class="text-sm opacity-90">

                            Sedang Ditangani

                        </p>

                        <h2 class="text-4xl font-bold mt-2">

                            {{ $totalDitangani }}

                        </h2>

                    </div>

                    <x-heroicon-o-shield-check class="w-12 h-12 opacity-30" />

                </div>

            </div>

            {{-- Ditolak --}}
            <div class="bg-gradient-to-r from-red-600 to-red-500 rounded-xl shadow text-white p-5">

                <div class="flex justify-between items-center">

                    <div>

                        <p class="text-sm opacity-90">

                            Tidak Direkomendasikan

                        </p>

                        <h2 class="text-4xl font-bold mt-2">

                            {{ $totalDitolak }}

                        </h2>

                    </div>

                    <x-heroicon-o-x-circle class="w-12 h-12 opacity-30" />

                </div>

            </div>

        </div>

        {{-- FILTER --}}
        <div class="bg-white rounded-xl shadow p-6">

            <form method="GET">

                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">

                    {{-- Cari --}}
                    <div class="md:col-span-2">

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari pelapor, desa..."
                            class="w-full border rounded-lg px-4 py-2 focus:ring-indigo-500">

                    </div>

                    {{-- Status --}}
                    <div>

                        <select name="status" class="w-full border rounded-lg px-3 py-2">

                            <option value="">

                                Semua Status

                            </option>

                            <option value="BELUM_DITANGANI" {{ request('status') == 'BELUM_DITANGANI' ? 'selected' : '' }}>

                                Belum Ditangani

                            </option>

                            <option value="DITANGANI" {{ request('status') == 'DITANGANI' ? 'selected' : '' }}>

                                Ditangani

                            </option>

                            <option value="SELESAI" {{ request('status') == 'SELESAI' ? 'selected' : '' }}>

                                Selesai

                            </option>

                            <option value="TIDAK_DIREKOMENDASIKAN"
                                {{ request('status') == 'TIDAK_DIREKOMENDASIKAN' ? 'selected' : '' }}>

                                Tidak Direkomendasikan

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

                        <button class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">

                            Filter

                        </button>

                        <a href="{{ route('kabid.pengaduan.index') }}"
                            class="bg-gray-200 hover:bg-gray-300 px-4 rounded-lg flex items-center">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

        {{-- TABEL --}}
        <div class="bg-white rounded-xl shadow overflow-hidden">

            <div class="px-6 py-4 border-b">

                <h3 class="font-semibold text-lg">

                    Daftar Pengaduan

                </h3>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-5 py-3 text-center text-xs uppercase">

                                Pelapor

                            </th>

                            <th class="px-5 py-3 text-left text-xs uppercase">

                                Informasi Pengaduan

                            </th>

                            <th class="px-5 py-3 text-center text-xs uppercase">

                                Foto

                            </th>

                            <th class="px-5 py-3 text-center text-xs uppercase">

                                Status

                            </th>

                            <th class="px-5 py-3 text-center text-xs uppercase">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">

                        @forelse($data as $d)
                            <tr class="hover:bg-gray-50 transition">

                                {{-- Pelapor --}}
                                <td class="px-5 py-4">

                                    <div class="font-semibold text-gray-800">

                                        {{ $d->user->nama ?? '-' }}

                                    </div>

                                    <div class="flex items-center gap-1 text-xs text-gray-500 mt-1">

                                        <x-heroicon-o-calendar class="w-3 h-3" />

                                        {{ $d->created_at->format('d M Y') }}

                                    </div>

                                </td>

                                {{-- Kategori --}}
                                <td class="px-5 py-4">

                                    <div class="inline-flex items-center gap-2">

                                        <x-heroicon-o-tag class="w-4 h-4 text-blue-500" />

                                        <span class="font-medium">

                                            {{ $d->kategori->nama_kategori ?? '-' }}

                                        </span>

                                    </div>

                                </td>

                                {{-- Desa --}}
                                <td class="px-5 py-4">

                                    <div class="font-semibold text-gray-800">

                                        {{ $d->desa }}

                                    </div>

                                    <div class="text-xs text-gray-500 mt-1">

                                        {{ \Illuminate\Support\Str::limit($d->deskripsi, 50) }}

                                    </div>

                                </td>

                                {{-- Foto --}}
                                <td class="px-5 py-4 text-center">

                                    @if ($d->foto->count())
                                        <span
                                            class="inline-flex items-center gap-1 bg-sky-100 text-sky-700 px-3 py-1 rounded-full text-xs font-medium">

                                            <x-heroicon-o-camera class="w-4 h-4" />

                                            {{ $d->foto->count() }} Foto

                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs">

                                            <x-heroicon-o-photo class="w-4 h-4" />

                                            Tidak Ada

                                        </span>
                                    @endif

                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-4 text-center">

                                    @if ($d->status_pengaduan == 'BELUM_DITANGANI')
                                        <span
                                            class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-medium">

                                            <x-heroicon-o-clock class="w-4 h-4" />

                                            Belum Ditangani

                                        </span>
                                    @elseif($d->status_pengaduan == 'DITANGANI')
                                        <span
                                            class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-medium">

                                            <x-heroicon-o-arrow-path class="w-4 h-4" />

                                            Ditangani

                                        </span>
                                    @elseif($d->status_pengaduan == 'SELESAI')
                                        <span
                                            class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">

                                            <x-heroicon-o-check-circle class="w-4 h-4" />

                                            Selesai

                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">

                                            <x-heroicon-o-x-circle class="w-4 h-4" />

                                            Tidak Direkomendasikan

                                        </span>
                                    @endif

                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-4 text-center">

                                    @if ($d->status_pengaduan == 'SELESAI')
                                        <span
                                            class="inline-flex items-center gap-2 bg-green-100 text-green-700 px-4 py-2 rounded-lg text-xs font-semibold">

                                            <x-heroicon-o-check-circle class="w-4 h-4" />

                                            Sudah Diverifikasi

                                        </span>
                                    @else
                                        <a href="{{ route('kabid.pengaduan.verifikasi', $d->id) }}"
                                            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-xs font-medium transition">

                                            <x-heroicon-o-check-badge class="w-4 h-4" />

                                            Verifikasi

                                        </a>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="py-14 text-center">

                                    <div class="flex flex-col items-center">

                                        <x-heroicon-o-document-text class="w-14 h-14 text-gray-300" />

                                        <p class="mt-3 font-semibold text-gray-600">

                                            Belum ada data pengaduan

                                        </p>

                                        <p class="text-sm text-gray-400">

                                            Tidak ada pengaduan yang perlu diverifikasi.

                                        </p>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>
                </table>

            </div>

            {{-- Footer Table --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 px-6 py-4 border-t bg-gray-50">

                <div class="text-sm text-gray-500">

                    Menampilkan
                    <span class="font-semibold">

                        {{ $data->count() }}

                    </span>
                    data pengaduan.

                </div>

                <div class="text-sm text-gray-500">

                    @if (request()->filled('search') ||
                            request()->filled('status') ||
                            request()->filled('bulan') ||
                            request()->filled('tahun'))
                        Filter sedang digunakan
                    @else
                        Semua data ditampilkan
                    @endif

                </div>

            </div>

        </div>

    </div>
@endsection
