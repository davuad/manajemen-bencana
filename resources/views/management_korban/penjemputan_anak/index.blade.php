@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="bg-white rounded-xl shadow p-6">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-3xl font-bold">Penjemputan Anak</h2>
                <p class="text-gray-500 text-sm">Kelola data penjemputan anak</p>
            </div>
        </div>

        {{-- FILTER --}}
        <form method="GET" class="mb-4 flex gap-2">
            <select name="status" class="border rounded-lg px-4 py-2">
                <option value="">Semua Status</option>
                <option value="menunggu">Menunggu</option>
                <option value="valid">Sudah Dijemput</option>
            </select>

            <input type="text" name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama anak"
                   class="border rounded-lg px-4 py-2 w-72">

            <button class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                Cari
            </button>
        </form>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Foto</th>
                        <th>Nama Anak</th>
                        <th>Umur</th>
                        <th>Lokasi</th>
                        <th>Penjemput</th>
                        <th>Petugas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($data as $item)
                    <tr class="border-t">
                        <td class="p-3">
                            @if($item->foto_anak)
                                <img src="{{ asset('storage/'.$item->foto_anak) }}"
                                     class="w-14 h-14 rounded object-cover">
                            @endif
                        </td>

                        <td>{{ $item->nama_anak }}</td>
                        <td>{{ $item->umur ?? '-' }}</td>
                        <td>{{ $item->lokasi_ditemukan }}</td>

                        {{-- PENJEMPUT --}}
                        <td>
                            {{ $item->penjemputan->penjemput->nama_penjemput ?? '-' }}
                        </td>

                        {{-- PETUGAS --}}
                        <td>
                            {{ $item->penjemputan->petugas->nama ?? '-' }}
                        </td>

                        {{-- STATUS --}}
                        <td>
                            @if(optional($item->penjemputan)->status_verifikasi == 'valid')
                                <span class="bg-green-200 text-green-800 px-2 py-1 rounded text-xs">
                                    Sudah Dijemput
                                </span>
                            @elseif(optional($item->penjemputan))
                                <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded text-xs">
                                    Menunggu
                                </span>
                            @else
                                <span class="bg-red-200 text-red-700 px-2 py-1 rounded text-xs">
                                    Belum Dijemput
                                </span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="flex gap-2">
                            @if(!$item->penjemputan)
                                <a href="{{ route('admin.penjemputan.jemput', $item->id) }}"
                                   class="bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                    Jemput
                                </a>
                            @else
                                <a href="{{ route('admin.penjemputan.show', $item->penjemputan->id) }}"
                                   class="bg-indigo-500 text-white px-3 py-1 rounded text-xs">
                                    Detail
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center p-4">
                            Belum ada data
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection