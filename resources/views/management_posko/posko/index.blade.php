@extends('layouts.app')

@php
    // Definisikan role user dinamis di bagian paling atas agar bisa dipakai di seluruh elemen rute
    $userRole = auth()->user()->roles->first()->name ?? 'admin';
@endphp

@section('content')
    <div class="bg-white rounded-xl shadow p-6">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold">Olah Data Posko</h2>
                <p class="text-gray-500 text-sm">
                    Kelola informasi titik posko darurat bencana
                </p>
            </div>
            @hasanyrole('admin|pegawai|petugas|relawan')
                <a href="{{ route('management_posko.posko.create') }}"
                    class="bg-indigo-700 text-white px-4 py-2 rounded-lg inline-block">
                    + Tambah Data Posko
                </a>
            @endhasanyrole
        </div>

        <form method="GET" action="{{ route('management_posko.posko.index', ['role' => $userRole]) }}">
            <div class="flex gap-4 mb-6">

                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari berdasarkan Nama Posko atau ID posko"
                    class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">

                <select name="desa" class="border rounded-lg py-2">
                    <option value="">Semua Desa Terdampak</option>
                    @foreach ($desa as $d)
                        <option value="{{ $d->id }}" {{ request('desa') == $d->id ? 'selected' : '' }}>
                            {{ $d->nama_desa }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
                    Filter
                </button>

            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-center">No</th>
                        <th class="text-center">Nama Posko</th>
                        <th class="text-left pl-4">Desa</th>
                        <th class="text-center">Nama Bencana</th>
                        <th class="text-center">Deskripsi Bencana</th>
                        <th class="text-left">Lokasi</th>
                        <th class="text-left">Tanggal</th>
                        <th class="text-left pl-4">Status</th>
                        @hasanyrole('admin|pegawai|petugas|relawan')

                            <th class="text-center">Aksi</th>
                        @endhasanyrole
                    </tr>
                </thead>

                <tbody>
                    @forelse($posko as $key => $p)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3 text-center">{{ $posko->firstItem() + $key }}</td>
                            <td class="p-3 text-center font-medium text-gray-800">{{ $p->nama_posko }}</td>
                            <td class="p-3 pl-4">{{ $p->desa->nama_desa ?? '-' }}</td>
                            <td class="p-3 pl-4 text-center">{{ $p->bencana->nama_bencana ?? '-' }}</td>
                            <td class="p-3 text-center text-gray-500">{{ $p->pengaduan->deskripsi ?? '-' }}</td>
                            <td class="p-3">{{ $p->lokasi }}</td>
                            <td class="p-3">{{ $p->tanggal_dibuat }}</td>
                            <td class="p-3 pl-4">
                                @if ($p->status == 'aktif')
                                    <span class="inline-block px-3 py-1 rounded-full bg-green-200 text-green-800 font-semibold text-xs">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-full bg-red-200 text-red-700 font-semibold text-xs opacity-70">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            @hasanyrole('admin|pegawai|petugas|relawan')

                            <td class="p-3">
                                <div class="flex justify-center gap-3">
                                    {{-- PERBAIKAN EDIT: Menggunakan parameter 'posko' sesuai Resource standarisasi Laravel --}}
                                    <a href="{{ route('management_posko.posko.edit', ['role' => $userRole, 'posko' => $p->id]) }}"
                                        class="text-blue-500 hover:text-blue-700">
                                        <x-heroicon-o-pencil-square class="w-5 h-5" />
                                    </a>

                                    <button onclick="openModal('{{ $p->id }}', '{{ $p->nama_posko }}')"
                                        class="text-red-500 hover:text-red-700">
                                        <x-heroicon-o-trash class="w-5 h-5" />
                                    </button>
                                </div>
                            </td>
                            @endhasanyrole
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center p-6 text-gray-500">
                                Data posko belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center mt-6 text-sm">
            <p class="text-gray-500">
                Menampilkan {{ $posko->firstItem() ?? 0 }} - {{ $posko->lastItem() ?? 0 }} dari {{ $posko->total() }} data posko
            </p>

            <div>
                {{ $posko->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 backdrop-blur-sm bg-black/20 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">
            <div class="flex items-start gap-3">
                <div class="bg-red-100 p-2 rounded-full">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-500" />
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Hapus Data Posko</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Apakah Anda yakin ingin menghapus data posko <span id="namaPosko" class="font-semibold"></span>?
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button onclick="closeModal()" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">
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
        function openModal(id, nama) {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.getElementById('namaPosko').innerText = `"${nama}"`;

            // PERBAIKAN DESTROY: Menambahkan parameter token array yang lengkap di URL JavaScript
            let url = "{{ route('management_posko.posko.destroy', ['role' => $userRole, 'posko' => ':id']) }}";
            url = url.replace(':id', id);

            document.getElementById('deleteForm').action = url;
        }

        function closeModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
@endsection