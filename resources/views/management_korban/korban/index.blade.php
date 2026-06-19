@php
    $routePrefix = auth()->user()->hasRole('admin')
        ? 'admin.korban'
        : (auth()->user()->hasRole('petugas')
            ? 'petugas.korban'
            : 'relawan.korban');
@endphp

@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">

        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">

            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Olah Data Korban
                </h2>

                <p class="text-gray-500 text-sm mt-1">
                    Kelola data korban meninggal akibat bencana
                </p>
            </div>

            <a href="{{ route($routePrefix . '.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition">
                + Tambah Data Korban
            </a>

        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div id="alert-success"
                class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-green-700 transition-opacity duration-500">
                {{ session('success') }}
            </div>

            <script>
                setTimeout(() => {
                    const alert = document.getElementById('alert-success');
                    if (alert) {
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500); // hilang total
                    }
                }, 2000); // 2 detik
            </script>
        @endif

        {{-- FILTER --}}
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 mb-6">

            <div class="flex items-center gap-2 mb-4">

                <x-heroicon-o-funnel class="w-5 h-5 text-indigo-600" />

                <h3 class="font-semibold text-gray-800">
                    Filter Data Korban
                </h3>

            </div>

            <form id="filterForm"
                method="GET"
                action="{{ route($routePrefix . '.index') }}">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-3">

                    {{-- Tahun --}}
                    <div class="lg:col-span-2">
                        <select
                            name="tahun"
                            class="auto-submit w-full border rounded-lg py-2.5 px-3">

                            @foreach($tahunList as $item)
                                <option value="{{ $item }}"
                                    {{ ($tahun ?? now()->year) == $item ? 'selected' : '' }}>
                                    Tahun {{ $item }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Bencana --}}
                    <div class="lg:col-span-3">
                        <select
                            name="bencana_id"
                            class="auto-submit w-full border rounded-lg py-2.5 px-3">

                            <option value="">
                                Semua Bencana
                            </option>

                            @foreach ($bencana as $item)

                                <option value="{{ $item->id }}"
                                    {{ request('bencana_id') == $item->id ? 'selected' : '' }}>

                                    {{ $item->kategori->nama_kategori ?? '-' }}
                                    -
                                    {{ $item->desa->nama_desa ?? '-' }}
                                    -
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}

                                </option>

                            @endforeach

                        </select>
                    </div>

                    {{-- Posko --}}
                    <div class="lg:col-span-3">
                        <select
                            name="posko_id"
                            class="auto-submit w-full border rounded-lg py-2.5 px-3">

                            <option value="">
                                Semua Posko
                            </option>

                            @foreach ($posko as $item)

                                <option value="{{ $item->id }}"
                                    {{ request('posko_id') == $item->id ? 'selected' : '' }}>

                                    {{ $item->nama_posko }}

                                </option>

                            @endforeach

                        </select>
                    </div>

                    {{-- Search --}}
                    <div class="lg:col-span-4">
                        <input
                            id="searchInput"
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama atau NIK korban..."
                            class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                </div>

                <div class="flex justify-between items-center mt-4">

                    {{-- Badge Filter Aktif --}}
                    <div class="flex flex-wrap gap-2">

                        <span class="bg-indigo-100 text-indigo-700 text-xs px-3 py-1 rounded-full">
                            Tahun {{ $tahun }}
                        </span>

                        @if(request('bencana_id'))
                            <span class="bg-orange-100 text-orange-700 text-xs px-3 py-1 rounded-full">
                                Bencana Dipilih
                            </span>
                        @endif

                        @if(request('posko_id'))
                            <span class="bg-emerald-100 text-emerald-700 text-xs px-3 py-1 rounded-full">
                                Posko Dipilih
                            </span>
                        @endif

                        @if(request('search'))
                            <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full">
                                "{{ request('search') }}"
                            </span>
                        @endif

                    </div>

                    {{-- Action --}}
                    <div class="flex items-center gap-2">

                        <a href="{{ route($routePrefix . '.index') }}"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded-lg"
                            title="Bersihkan Filter">

                            <x-heroicon-o-x-mark class="w-5 h-5" />

                        </a>

                        <a href="{{ route($routePrefix . '.reviewPdf', request()->query()) }}"
                            target="_blank"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">

                            <x-heroicon-o-document-arrow-down class="w-5 h-5" />

                            PDF

                        </a>

                    </div>

                </div>

            </form>

        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-center">No</th>
                        <th class="text-left pl-4">Nama</th>
                        <th class="text-left">NIK</th>
                        <th class="text-center">Umur</th>
                        <th class="text-left">Bencana</th>
                        <th class="text-left">Posko</th>
                        <th class="text-left">Tanggal Kejadian</th>
                        <th class="text-left">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($korban as $key => $item)
                        <tr class="border-t">
                            <td class="p-3 text-center">
                                {{ $korban->firstItem() + $key }}
                            </td>

                            <td class="p-3 pl-4">{{ $item->nama }}</td>
                            <td class="p-3">{{ $item->nik ?? '-' }}</td>
                            <td class="p-3 text-center">{{ $item->umur }}</td>
                            <td class="p-3">
                                {{ $item->bencana->kategori->nama_kategori ?? '-' }} - {{ $item->bencana->desa->nama_desa ?? '-' }} - {{ \Carbon\Carbon::parse($item->bencana->tanggal)->format('d-m-Y') }}
                            </td>
                            <td class="p-3">{{ $item->posko->nama_posko ?? '-' }}</td>
                            <td class="p-3">
                                {{ \Carbon\Carbon::parse($item->tanggal_kejadian)->format('d-m-Y') }}
                            </td>

                            <td class="py-3">
                                <div class="flex gap-2 items-center">
                                    <a href="{{ route($routePrefix . '.show', $item->id) }}"
                                        class="text-green-600 hover:text-green-800" title="Detail">
                                        <x-heroicon-o-eye class="w-5 h-5" />
                                    </a>

                                    <a href="{{ route($routePrefix . '.edit', $item->id) }}"
                                        class="text-blue-500 hover:text-blue-700" title="Edit">
                                        <x-heroicon-o-pencil-square class="w-5 h-5" />
                                    </a>

                                    <button type="button"
                                        onclick="openModal('{{ $item->id }}', '{{ addslashes($item->nama) }}')"
                                        class="text-red-500 hover:text-red-700" title="Hapus">
                                        <x-heroicon-o-trash class="w-5 h-5" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center p-4">
                                Data korban belum ada
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center mt-6 text-sm">
            <p class="text-gray-500">
                Menampilkan {{ $korban->firstItem() ?? 0 }} - {{ $korban->lastItem() ?? 0 }}
                dari {{ $korban->total() }} data korban
            </p>

            <div>
                {{ $korban->withQueryString()->links() }}
            </div>
        </div>

    </div>

    <div id="deleteModal" class="fixed inset-0 backdrop-blur-sm bg-white/10 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">

            <div class="flex items-start gap-3">
                <div class="bg-red-100 p-2 rounded-full">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-500" />
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Hapus Data Korban</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Apakah Anda yakin ingin menghapus data korban
                        <span id="namaKorban" class="font-semibold"></span>?
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">
                    Batal
                </button>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600">
                        Hapus Data
                    </button>
                </form>
            </div>

        </div>
    </div>

    <script>

        const filterForm = document.getElementById('filterForm');

        document.querySelectorAll('.auto-submit')
            .forEach(element => {

                element.addEventListener('change', () => {
                    filterForm.submit();
                });

            });

        let debounce;

        document.getElementById('searchInput')
            .addEventListener('input', function () {

                clearTimeout(debounce);

                debounce = setTimeout(() => {
                    filterForm.submit();
                }, 700);

            });

    </script>
@endsection
