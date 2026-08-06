@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold">Data Barang Masuk</h2>
            <p class="text-gray-500 text-sm">
                Kelola data transaksi barang masuk
            </p>
        </div>

        <a href="{{ route('admin.barang-masuk.create') }}"
           class="bg-indigo-700 text-white px-4 py-2 rounded-lg">
            + Tambah Barang Masuk
        </a>
    </div>

    <!-- SEARCH + FILTER -->
<form method="GET" action="{{ route('admin.barang-masuk.index') }}" class="mb-5">

    <div class="flex flex-wrap items-center gap-3">

        <!-- Search -->
        <input
            type="text"
            name="search"
            value="{{ $search ?? '' }}"
            placeholder="Cari ID, no dokumen, atau status..."
            class="w-96 border rounded-lg p-3">

        <!-- Tombol Search -->
        <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg">
            🔍 Search
        </button>

        <!-- Bulan -->
        <select
            name="bulan"
            class="border rounded-lg p-3 w-44">

            <option value="">Semua Bulan</option>

            @foreach(range(1,12) as $b)
                <option value="{{ $b }}"
                    {{ ($bulan == $b) ? 'selected' : '' }}>
                    {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                </option>
            @endforeach

        </select>

        <!-- Tahun -->
        <select
            name="tahun"
            class="border rounded-lg p-3 w-36">

            <option value="">Semua Tahun</option>

            @for($t = date('Y'); $t >= 2020; $t--)
                <option value="{{ $t }}"
                    {{ ($tahun == $t) ? 'selected' : '' }}>
                    {{ $t }}
                </option>
            @endfor

        </select>

        <!-- Filter -->
        <button
            type="submit"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-lg">
            Filter
        </button>

        <!-- Reset -->
        <a href="{{ route('admin.barang-masuk.index') }}"
            class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg">
            Reset
        </a>

    </div>

</form>

    <!-- TABLE -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-center">No</th>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Sumber</th>
                    <th>Gudang</th>
                    <th>Status</th>
                    <th>No Dokumen</th>
                    <th>Penerima</th>
                    <th>Keterangan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($data as $index => $item)
                <tr class="border-t">
                    <td class="p-2 text-center">{{ $index + 1 }}</td>
                    <td class="p-2">{{ $item->id_barang_masuk }}</td>
                    <td class="p-2">{{ $item->tgl_masuk }}</td>

                    <!-- RELASI SUMBER -->
                    <td class="p-2">
                        {{ $item->sumber->nama_sumber ?? '-' }}
                    </td>

                    <!-- GUDANG -->
                    <td class="p-2">
                        {{ $item->gudang->nama_gudang ?? '-' }}
                    </td>

                    <!-- STATUS -->
                    <td class="p-2">
                        <span class="px-2 py-1 rounded text-white text-xs
                            {{ $item->status == 'selesai' ? 'bg-green-500' : 'bg-yellow-500' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>

                    <!-- NO DOKUMEN -->
                    <td class="p-2">
                        {{ $item->no_dokumen }}
                    </td>

                    <!-- PENERIMA (PEGAWAI) -->
                    <td class="p-2">   
                        {{ $item->pegawai->nama_pegawai ?? '-' }}
                    </td>

                    <!-- KETERANGAN -->
                    <td class="p-2">
                        {{ $item->keterangan }}
                    </td>

                    <!-- AKSI -->
                    <td class="p-2 text-center">
                        
                        <!-- DETAIL -->
                        <a href="{{ route('admin.barang-masuk.show', $item->id_barang_masuk) }}"
                            class="text-gray-700 mr-2">👁️</a>

                        <!-- EDIT -->
                        <a href="{{ route('admin.barang-masuk.edit', $item->id_barang_masuk) }}"
                            class="text-blue-500 mr-2">✏️</a>

                        <!-- DELETE -->
                        <form action="{{ route('admin.barang-masuk.destroy', $item->id_barang_masuk) }}"
                                method="POST"
                                class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-500">🗑️</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center p-4">
                        Data belum ada
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection