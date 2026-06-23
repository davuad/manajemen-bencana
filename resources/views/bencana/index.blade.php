@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold">DATA BENCANA</h2>
                <p class="text-gray-500 text-sm">Data kejadian bencana</p>
            </div>

            <a href="{{ route('admin.bencana.create') }}" class="bg-indigo-700 text-white px-4 py-2 rounded-lg">
                + Tambah Bencana
            </a>
        </div>

        {{-- FILTER --}}
        <form method="GET">
            <div class="flex flex-wrap gap-4 mb-6">

                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..."
                    class="flex-1 border rounded-lg px-4 py-2">

                <select
                name="kategori_id"
                class="border rounded-lg px-4 py-2 pr-10 appearance-none bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategori as $k)
                        <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>

                <select name="tingkat_kerusakan" class="border rounded-lg px-4 py-2 pr-10 appearance-none bg-white">
                    <option value="">Semua Kerusakan</option>
                    <option value="ringan">Ringan</option>
                    <option value="sedang">Sedang</option>
                    <option value="parah">Parah</option>
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
                        <th class="p-3 text-center">No</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Kategori</th>
                        <th class="text-center">Desa</th>
                        <th class="text-center">Pengaduan</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Kerusakan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($bencana as $key => $b)
                        <tr class="border-t hover:bg-gray-50">

                            <td class="text-center p-2">
                                {{ $bencana->firstItem() + $key }}
                            </td>

                            <td class="text-center font-semibold">
                                {{ $b->nama_bencana }}
                            </td>

                            <td class="text-center">
                                {{ $b->kategori->nama_kategori ?? '-' }}
                            </td>

                            <td class="text-center">
                                {{ $b->desa->nama_desa ?? '-' }}
                            </td>

                            <td class="text-center">
                                {{ $b->pengaduan->deskripsi ?? '-' }}
                            </td>

                            <td class="text-center">
                                {{ $b->tanggal }}
                            </td>

                            <td class="text-center">
                                @if ($b->status_bencana == 'selesai')
                                    <span class="bg-green-200 text-green-800 px-3 py-1 rounded-full">
                                        Selesai
                                    </span>
                                @else
                                    <span class="bg-orange-200 text-orange-800 px-3 py-1 rounded-full">
                                        Berlangsung
                                    </span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($b->tingkat_kerusakan == 'parah')
                                    <span class="bg-red-200 text-red-800 px-3 py-1 rounded-full">Parah</span>
                                @elseif($b->tingkat_kerusakan == 'sedang')
                                    <span class="bg-orange-200 text-orange-800 px-3 py-1 rounded-full">Sedang</span>
                                @else
                                    <span class="bg-green-200 text-green-800 px-3 py-1 rounded-full">Ringan</span>
                                @endif
                            </td>

                            <td class="flex justify-center gap-2 py-2">
                                <a href="{{ route('admin.bencana.edit', $b->id) }}" class="text-blue-500">✏️</a>

                                <form action="{{ route('admin.bencana.destroy', $b->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-500">🗑️</button>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center p-4 text-gray-500">
                                Data belum ada
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="mt-4">
            {{ $bencana->links() }}
        </div>

    </div>
@endsection
