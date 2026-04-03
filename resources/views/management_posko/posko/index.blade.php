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

        <button class="bg-indigo-700 text-white px-4 py-2 rounded-lg">
            + Tambah Data Posko
        </button>

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
                    <th class="p-3 text-left">No</th>
                    <th class="text-left">ID Posko</th>
                    <th class="text-left">Nama Posko</th>
                    <th class="text-left">Lokasi</th>
                    <th class="text-left">Desa</th>
                    <th class="text-left">Tanggal</th>
                    <th class="text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($poskos as $index => $posko)
                <tr class="border-b hover:bg-gray-50">

                    <td class="p-3">{{ $index + 1 }}</td>
                    <td>{{ $posko['id'] }}</td>
                    <td>{{ $posko['nama'] }}</td>
                    <td>{{ $posko['lokasi'] }}</td>
                    <td>{{ $posko['desa'] }}</td>
                    <td>{{ $posko['tanggal'] }}</td>

                    <td class="flex gap-2 py-3">
                        <button class="text-blue-500">Edit</button>
                        <button class="text-red-500">Hapus</button>
                    </td>

                </tr>
                @endforeach

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