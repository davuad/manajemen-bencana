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

        <a href="/barang-masuk/create"
           class="bg-indigo-700 text-white px-4 py-2 rounded-lg">
            + Tambah Barang Masuk
        </a>
    </div>

    <!-- ALERT -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- SEARCH -->
    <form method="GET" action="/barang-masuk" class="mb-4">
        <input type="text"
                name="search"
                value="{{ $search ?? '' }}"
                placeholder="Cari ID, no dokumen, atau status..."
                class="w-full border rounded-lg p-3">
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
                        <a href="/barang-masuk/{{ $item->id_barang_masuk }}"
                            class="text-gray-700 mr-2">👁️</a>

                        <!-- EDIT -->
                        <a href="/barang-masuk/{{ $item->id_barang_masuk }}/edit"
                           class="text-blue-500 mr-2">✏️</a>

                        <!-- DELETE -->
                        <form action="/barang-masuk/{{ $item->id_barang_masuk }}"
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