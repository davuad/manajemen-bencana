@extends('layouts.app')

@section('content')

<div class="py-6">
    <div class="bg-white rounded-xl shadow p-6">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold">Data Anak Terpisah</h2>
                <p class="text-gray-500 text-sm">
                    Kelola data anak yang ditemukan
                </p>
            </div>

            <a href="{{ route('admin.anak_terpisah.create') }}" 
               class="bg-indigo-700 text-white px-4 py-2 rounded-lg">
                + Tambah Data
            </a>
        </div>

        <form method="GET" action="{{ route('admin.anak_terpisah.index') }}" class="mb-4 flex flex-wrap gap-2">
            <!-- untuk search berdasarkan nama anak-->
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama anak..."
                class="border border-gray-300 rounded-lg px-4 py-2 w-64"
            >
            <!-- untuk memfilter berdasarkan umur-->
            <select
                name="filter_umur"
                class="border border-gray-300 rounded-lg px-4 py-2"
            >
                <option value="">Semua Umur</option>
                <option value="0-2" {{ request('filter_umur') == '0-2' ? 'selected' : '' }}>0 - 2 Tahun</option>
                <option value="3-5" {{ request('filter_umur') == '3-5' ? 'selected' : '' }}>3 - 5 Tahun</option>
                <option value="6-8" {{ request('filter_umur') == '6-8' ? 'selected' : '' }}>6 - 8 Tahun</option>
                <option value="9-11" {{ request('filter_umur') == '9-11' ? 'selected' : '' }}>9 - 11 Tahun</option>
                <option value="12-14" {{ request('filter_umur') == '12-14' ? 'selected' : '' }}>12 - 14 Tahun</option>
                <option value="15-17" {{ request('filter_umur') == '15-17' ? 'selected' : '' }}>15 - 17 Tahun</option>
            </select>

            <button
                type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg"
            >
                Cari
            </button>

            <a href="{{ route('admin.anak_terpisah.index') }}"
            class="bg-gray-300 px-4 py-2 rounded-lg">
                Reset
            </a>
        </form>

        <!--informasi untuk menampilkan hasil kata kunci-->
        @if(request('search') || request('filter_umur'))
            <p class="text-sm text-gray-500 mb-3">
                Menampilkan hasil
                @if(request('search'))
                    untuk kata kunci <span class="font-semibold">"{{ request('search') }}"</span>
                @endif

                @if(request('filter_umur'))
                    dengan filter umur <span class="font-semibold">{{ request('filter_umur') }} tahun</span>
                @endif
            </p>
        @endif

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-center">No</th>
                        <th class="text-center">Foto</th>
                        <th class="text-left">Nama</th>
                        <th class="text-left">Jenis Kelamin</th>
                        <th class="text-left">Umur</th>
                        <th class="text-left">Lokasi</th>
                        <th class="text-left">Status</th>
                        <th class="text-left">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $d)
                    <tr class="border-t">
                        <td class="p-2 text-center">{{ $loop->iteration }}</td>

                        <td class="p-2 text-center">
                            <img src="{{ asset('storage/' . $d->foto_anak) }}" 
                                 class="w-16 h-16 object-cover rounded">
                        </td>

                        <td class="p-2">{{ $d->nama_anak }}</td>
                        <td class="p-2">{{ $d->jenis_kelamin }}</td>
                        <td class="p-2">{{ $d->umur ?? '-' }}</td>
                        <td class="p-2">{{ $d->lokasi_ditemukan }}</td>

                        <td class="p-2">
                            @if($d->status_anak == 'sudah_dijemput')
                                <span class="px-3 py-1 bg-green-200 text-green-800 rounded-full text-xs">
                                    Sudah Dijemput
                                </span>
                            @elseif($d->status_anak == 'dalam_proses')
                                <span class="px-3 py-1 bg-yellow-200 text-yellow-800 rounded-full text-xs">
                                    Dalam Proses
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-200 text-red-700 rounded-full text-xs">
                                    Belum Dijemput
                                </span>
                            @endif
                        </td>

                        <td class="flex gap-2 py-4">
                            <!-- lihat detail data anak-->
                            <a href="{{ route('admin.anak_terpisah.show', $d->id) }}" class="text-gray-600 hover:text-black">
                                👁️
                            </a>

                            <!-- edit data anak-->
                            <a href="{{ route('admin.anak_terpisah.edit', $d->id) }}" class="text-blue-500">
                                ✏️
                            </a>

                            <button onclick="openModal('{{ $d->id }}', '{{ $d->nama_anak }}')" class="text-red-500">
                                🗑️
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center p-4">
                            {{ request('search') || request('filter_umur') ? 'Data anak tidak ditemukan' : 'Data belum ada' }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<div id="deleteModal" class="fixed inset-0 backdrop-blur-sm bg-black/30 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">
        <h2 class="text-lg font-semibold mb-2">Hapus Data</h2>

        <p class="text-sm text-gray-500">
            Yakin ingin menghapus 
            <span id="namaAnak" class="font-semibold"></span>?
        </p>

        <div class="flex justify-end gap-3 mt-6">
            <button onclick="closeModal()" class="px-4 py-2 bg-gray-200 rounded-lg">
                Batal
            </button>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function openModal(id, nama) {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');

    document.getElementById('namaAnak').innerText = `"${nama}"`;
    document.getElementById('deleteForm').action = `/anak/${id}`;
}

function closeModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>

@endsection