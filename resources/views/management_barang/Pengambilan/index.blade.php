@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold">Data Pengambilan</h2>
            <p class="text-gray-500 text-sm">Kelola data pengambilan barang dari posko</p>
        </div>
        <a href="{{ route('admin.management_barang.pengambilan.create') }}"
           class="bg-indigo-700 text-white px-4 py-2 rounded-lg inline-block hover:bg-indigo-800 transition">
            + Tambah Pengambilan
        </a>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('admin.management_barang.pengambilan.index') }}">
        <div class="flex gap-4 mb-6">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari tujuan / petugas / posko"
                   class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                Cari
            </button>
        </div>
    </form>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="p-3 text-center w-12">No</th>
                    <th class="p-3 text-left">Bencana</th>
                    <th class="p-3 text-left">Petugas</th>
                    <th class="p-3 text-left">Posko</th>
                    <th class="p-3 text-left">Tanggal</th>
                    <th class="p-3 text-left">Tujuan</th>
                    <th class="p-3 text-center">Gambar</th>
                    <th class="p-3 text-center w-28">Status</th>
                    <th class="p-3 text-center w-28">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                <tr class="border-b hover:bg-gray-50 transition">
                    {{-- No --}}
                    <td class="p-3 text-center font-medium text-gray-600">
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

                    {{-- Posko --}}
                    <td class="p-3">
                        {{ $item->posko->nama_posko ?? '-' }}
                    </td>

                    {{-- Tanggal --}}
                    <td class="p-3 whitespace-nowrap">
                        {{ $item->tanggal_pengambilan }}
                    </td>

                    {{-- Tujuan --}}
                    <td class="p-3 max-w-xs truncate" title="{{ $item->tujuan }}">
                        {{ $item->tujuan }}
                    </td>

                    {{-- Gambar (Kolom Baru Berdasarkan Item Pertama/Relasi) --}}
                    <td class="p-3 text-center">
                        @php
                            // Mengambil foto dari relasi barangPengambilan jika strukturnya HasMany,
                            // atau langsung dari $item->gambar jika strukturnya Single/Flat.
                            $gambarUrl = null;
                            if (isset($item->barangPengambilan) && $item->barangPengambilan->count() > 0) {
                                $firstItem = $item->barangPengambilan->firstWhere('gambar', '!=', null);
                                if ($firstItem) {
                                    $gambarUrl = asset('storage/' . $firstItem->gambar);
                                }
                            } elseif (isset($item->gambar) && $item->gambar) {
                                $gambarUrl = asset('storage/' . $item->gambar);
                            }
                        @endphp

                        @if($gambarUrl)
                            <a href="{{ $gambarUrl }}" target="_blank" class="inline-block group relative">
                                <img src="{{ $gambarUrl }}" 
                                     alt="Preview" 
                                     class="w-10 h-10 object-cover rounded border shadow-sm group-hover:scale-105 transition">
                            </a>
                        @else
                            <span class="text-xs text-gray-400 italic">No image</span>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td class="p-3 text-center whitespace-nowrap">
                        @if($item->status == 'Ditangani')
                            <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 font-semibold border border-yellow-200">
                                Ditangani
                            </span>
                        @elseif($item->status == 'Selesai')
                            <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800 font-semibold border border-green-200">
                                Selesai
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700 font-semibold border border-red-200">
                                Dibatalkan
                            </span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td class="p-3 text-center whitespace-nowrap">
                        <div class="flex justify-center items-center gap-2">
                            {{-- Detail --}}
                            <a href="{{ route('admin.management_barang.pengambilan.show', $item->id) }}" 
                               class="p-1.5 text-blue-500 hover:bg-blue-50 rounded transition" 
                               title="Detail Pengambilan">
                                🔍
                            </a>

                            {{-- Edit --}}
                            @if($item->status != 'Dibatalkan')
                                <a href="{{ route('admin.management_barang.pengambilan.edit', $item->id) }}" 
                                   class="p-1.5 text-yellow-500 hover:bg-yellow-50 rounded transition" 
                                   title="Edit Data">
                                    ✏️
                                </a>
                            @else
                                <span class="p-1.5 text-gray-300 cursor-not-allowed opacity-50" title="Tidak bisa edit data yang dibatalkan">✏️</span>
                            @endif

                            {{-- Batalkan --}}
                            @if($item->status != 'Dibatalkan')
                                <form action="{{ route('admin.management_barang.pengambilan.batal', $item->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengambilan ini? Stok akan dikembalikan.');"
                                      class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded transition" title="Batalkan Pengambilan">
                                        ❌
                                    </button>
                                </form>
                            @else
                                <span class="p-1.5 text-gray-300 cursor-not-allowed opacity-50" title="Sudah dibatalkan">❌</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center p-8 text-gray-500 font-medium">
                        Data pengambilan belum tersedia.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection