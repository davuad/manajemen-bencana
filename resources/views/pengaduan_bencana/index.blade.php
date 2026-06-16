
@extends('layouts.app')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">

        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Data Pengaduan Bencana
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Daftar laporan pengaduan masyarakat terkait bencana
            </p>
        </div>

        <a href="{{ route('admin.pengaduan_bencana.create') }}"
           class="bg-indigo-700 hover:bg-indigo-800 text-white px-5 py-2.5 rounded-xl text-sm font-medium shadow-sm transition duration-200">

            + Tambah Pengaduan

        </a>

    </div>

    {{-- FILTER --}}
    <form method="GET">

        <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 mb-6">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                {{-- SEARCH --}}
                <div class="md:col-span-2">

                    <label class="text-sm font-medium text-gray-600 mb-2 block">
                        Pencarian
                    </label>

                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari desa atau deskripsi..."
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-300 focus:outline-none">

                </div>

                {{-- KATEGORI --}}
                <div>

                    <label class="text-sm font-medium text-gray-600 mb-2 block">
                        Kategori
                    </label>

                    <select name="kategori"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-indigo-300 focus:outline-none">

                        <option value="">
                            Semua Kategori
                        </option>

                        @foreach(\App\Models\KategoriBencana::all() as $k)

                            <option value="{{ $k->id }}"
                                {{ request('kategori') == $k->id ? 'selected' : '' }}>

                                {{ $k->nama_kategori }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- STATUS --}}
                <div>

                    <label class="text-sm font-medium text-gray-600 mb-2 block">
                        Status
                    </label>

                    <select name="status"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-indigo-300 focus:outline-none">

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

                </div>

            </div>

            <div class="mt-5 flex justify-end">

                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium shadow-sm transition">

                    Filter Data

                </button>

            </div>

        </div>

    </form>

    {{-- TABLE --}}
    <div class="overflow-x-auto rounded-2xl border border-gray-100">

        <table class="w-full text-sm">

            <thead class="bg-gray-50 text-gray-700">

                <tr>

                    <th class="px-4 py-4 text-center font-semibold">
                        No
                    </th>

                    <th class="px-4 py-4 text-left font-semibold">
                        Pelapor
                    </th>

                    <th class="px-4 py-4 text-left font-semibold">
                        Lokasi & Pengaduan
                    </th>

                    <th class="px-4 py-4 text-center font-semibold">
                        Foto
                    </th>

                    <th class="px-4 py-4 text-center font-semibold">
                        Kebutuhan
                    </th>

                    <th class="px-4 py-4 text-center font-semibold">
                        Status
                    </th>

                    <th class="px-4 py-4 text-center font-semibold">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-100 bg-white">

                @forelse($data as $key => $d)

                <tr class="hover:bg-gray-50 transition duration-150">

                    {{-- NO --}}
                    <td class="px-4 py-4 text-center font-semibold text-gray-700">

                        {{ $key + 1 }}

                    </td>

                    {{-- PELAPOR --}}
                    <td class="px-4 py-4">

                        <div class="font-semibold text-gray-800">

                            {{ $d->user->nama ?? '-' }}

                        </div>

                    </td>

                    {{-- LOKASI --}}
                    <td class="px-4 py-4">

                        <div class="font-semibold text-gray-800">

                            {{ $d->desa }}

                        </div>

                        <div class="text-sm text-gray-500 mt-2 leading-relaxed">

                            {{ Str::limit($d->deskripsi, 80) }}

                        </div>

                        <div class="mt-3">

                            <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full">

                                {{ $d->kategori->nama_kategori ?? '-' }}

                            </span>

                        </div>

                    </td>

                    {{-- FOTO --}}
                    <td class="px-4 py-4 text-center">

                        @if($d->foto->count() > 0)

                            <a href="/admin/foto/{{ $d->foto[0]->id }}"
                               class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 font-medium">

                                📷 {{ $d->foto->count() }} Foto

                            </a>

                        @else

                            <span class="text-gray-400 text-sm">

                                Tidak Ada

                            </span>

                        @endif

                    </td>

                    {{-- KEBUTUHAN --}}
                    <td class="px-4 py-4 text-center">

                        @if($d->kebutuhan)

                            <a href="/admin/kebutuhan/{{ $d->kebutuhan->id }}"
                               class="inline-flex items-center gap-1 text-cyan-600 hover:text-cyan-800 font-medium">

                                📦 Lihat

                            </a>

                        @else

                            <span class="text-gray-400 text-sm">

                                Tidak Ada

                            </span>

                        @endif

                    </td>

                   {{-- STATUS --}}
                    <td class="px-4 py-4 text-center">

                        @if($d->status_pengaduan == 'BELUM_DITANGANI')

                            <span class="bg-gray-200 text-gray-700 text-xs px-3 py-1 rounded-full font-medium">
                                Belum Ditangani
                            </span>

                        @elseif($d->status_pengaduan == 'DITANGANI')

                            <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full font-medium">
                                Ditangani
                            </span>

                        @elseif($d->status_pengaduan == 'SELESAI')

                            <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-medium">
                                Selesai
                            </span>

                        @else

                            <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full font-medium">
                                Ditolak
                            </span>

                        @endif

                        <div class="text-xs text-gray-500 mt-2">

                            @if($d->tanggal_selesai)

                                {{ \Carbon\Carbon::parse($d->tanggal_selesai)->format('d M Y') }}

                            @else

                                -

                            @endif

                        </div>

                    </td>
                    {{-- AKSI --}}
                    <td class="px-4 py-4">

                        <div class="flex justify-center gap-2">

                            <a href="/admin/pengaduan/{{ $d->id }}"
                               class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1.5 rounded-lg text-xs transition">

                                Edit

                            </a>

                            <form action="/admin/pengaduan/{{ $d->id }}"
                                  method="POST">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        onclick="return confirm('Yakin ingin menghapus data ini?')"
                                        class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 rounded-lg text-xs transition">

                                    Hapus

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="7"
                        class="text-center py-10 text-gray-400">

                        Data pengaduan belum tersedia

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
