@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <div>
            <h2 class="text-xl font-bold">Olah Data Posko</h2>
            <p class="text-gray-500 text-sm">
                Kelola informasi titik posko darurat bencana
            </p>
        </div>

        <a href="{{ route('management_posko.posko.create') }}" class="bg-indigo-700 text-white px-4 py-2 rounded-lg inline-block">
            + Tambah Data Posko
        </a>

    </div>

    <div class="flex gap-4 mb-6">

        <input type="text"
               placeholder="Cari berdasarkan Nama Posko atau ID posko"
               class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">

        <select class="border rounded-lg px-4 py-2">
            <option>Semua Desa Terdampak</option>
        </select>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-center">No</th>
                    <th class="text-center">Nama Posko</th>
                    <th class="text-center">Desa</th>
                    <th class="text-center">Deskripsi Bencana</th>
                    <th class="text-center">Lokasi</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($posko as $key => $p)
                <tr class="border-t">
                    <td class="p-2">{{ $key + 1 }}</td>
                    <td class="p-2">{{ $p->nama_posko }}</td>
                    <td class="p-2">{{ $p->desa->nama_desa ?? '-' }}</td>
                    <td class="p-2">{{ $p->pengaduan->deskripsi ?? '-' }}</td>
                    <td class="p-2">{{ $p->tanggal_dibuat }}</td>
                    <td class="p-2">{{ $p->lokasi }}</td>

                    <td class="flex gap-1 py-3">
                        <button class="text-blue-500"><x-heroicon-o-pencil-square class="w-5 h-5" /></button>
                        <button class="text-red-500"><x-heroicon-o-trash class="w-5 h-5" /></button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center p-4">
                        Data belum ada
                    </td>
                </tr>
            @endforelse

            </tbody>

        </table>

    </div>

    <div class="flex justify-between items-center mt-6 text-sm">

        <p class="text-gray-500">
            Menampilkan data posko
        </p>

        <div class="flex gap-2">
            <button class="px-3 py-1 border rounded">1</button>
            <button class="px-3 py-1 border rounded">2</button>
        </div>

    </div>

</div>

@endsection