@extends('layouts.app')

@php
    // Definisikan role user dinamis di bagian paling atas agar bisa dipakai di seluruh elemen rute
    $userRole = auth()->user()->roles->first()->name ?? 'admin';
@endphp

@section('content')
    <div class="bg-white rounded-xl shadow p-6">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold">Olah Data Dapur Umum</h2>
                <p class="text-gray-500 text-sm">
                    Kelola data dapur umum untuk kebutuhan logistik warga
                </p>
            </div>

            @hasanyrole('admin|pegawai|petugas')
            <!-- PERBAIKAN: Tambahkan parameter ['role' => $userRole] -->
            <a href="{{ route('management_posko.dapur_umum.create', ['role' => $userRole]) }}"
                class="bg-indigo-700 text-white px-4 py-2 rounded-lg inline-block">
                + Tambah Data Dapur
            </a>
            @endhasanyrole
        </div>

        <!-- PERBAIKAN: Form Action tambahkan parameter ['role' => $userRole] -->
        <form method="GET" action="{{ route('management_posko.dapur_umum.index', ['role' => $userRole]) }}">
            <div class="flex gap-4 mb-6">

                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari berdasarkan Nama Dapur Umum atau ID Dapur Umum"
                    class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">

                <select name="posko" class="border rounded-lg py-2">
                    <option value="">Semua Posko</option>
                    @foreach ($posko as $p)
                        <option value="{{ $p->id }}" {{ request('posko') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_posko }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
                    Filter
                </button>

            </div>
        </form>

        <div class="bg-white rounded-2xl p-5 m-3 mt-5 shadow-sm overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-sm">
                        <th class="p-4 text-left">No</th>
                        <th class="p-4 text-left">Nama Dapur</th>
                        <th class="p-4 text-left">Posko</th>
                        <th class="p-4 text-left">Kapasitas</th>
                        <th class="p-4 text-left">Jumlah Warga</th>
                        <th class="p-4 text-left">Penanggung Jawab</th>
                        @hasanyrole('admin|pegawai|petugas')
                            <th class="p-4 text-center">Aksi</th>
                        @endhasanyrole
                    </tr>
                </thead>

                <tbody>
                    @forelse($dapur as $index => $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4">
                                {{ $dapur->firstItem() + $index }}
                            </td>

                            <td class="p-4 font-medium text-gray-800">
                                {{ $item->nama_dapur_umum }}
                            </td>

                            <td class="p-4">
                                {{ $item->posko->nama_posko ?? '-' }}
                            </td>

                            <td class="p-4">
                                {{ $item->kapasitas_warga }} Orang
                            </td>

                            <td class="p-4">
                                {{ $item->jumlah_warga }} Orang
                            </td>

                            <td class="p-4">
                                {{ $item->penanggung_jawab }}
                            </td>

                            <td class="p-4">
                                <div class="flex justify-center gap-2">
                                    @hasanyrole('admin|pegawai|petugas')
                                    
                                    <!-- Detail Kebutuhan (Sudah Aman menggunakan rute flat baru) -->
                                    <a href="{{ route('management_posko.kebutuhan_harian.index', ['role' => $userRole, 'dapur' => $item->id]) }}"
                                    class="px-3 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                                    Detail Kebutuhan
                                    </a>

                                    {{-- EDIT (PERBAIKAN: Tambahkan parameter 'role' dan ganti id menjadi dapur_umum sesuai standarisasi Resource Route Laravel) --}}
                                    <a href="{{ route('management_posko.dapur_umum.edit', ['role' => $userRole, 'dapur_umum' => $item->id]) }}"
                                        class="px-3 py-2 bg-yellow-500 text-white rounded-lg text-sm hover:bg-yellow-600">
                                        Edit
                                    </a>

                                    {{-- DELETE --}}
                                    <button onclick="openModal('{{ $item->id }}', '{{ $item->nama_dapur_umum }}')"
                                        class="px-3 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700">
                                        Hapus
                                    </button>
                                    @endhasanyrole
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-6 text-gray-500">
                                Data dapur umum belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center mt-6 text-sm">
            <p class="text-gray-500">
                Menampilkan {{ $dapur->firstItem() }} - {{ $dapur->lastItem() }} dari {{ $dapur->total() }} data dapur umum
            </p>
            <div>
                {{ $dapur->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- MODAL HAPUS -->
    <div id="deleteModal"
        class="fixed inset-0 backdrop-blur-sm bg-white/10 hidden items-center justify-center z-50">

        <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">
            <!-- Header -->
            <div class="flex items-start gap-3">
                <div class="bg-red-100 p-2 rounded-full">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-500" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        Hapus Data Dapur Umum
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Apakah Anda yakin ingin menghapus data dapur umum <span id="namaDapur" class="font-semibold"></span>?
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>

            <!-- Action -->
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">
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

        document.getElementById('namaDapur').textContent = `"${nama}"`;

        // PERBAIKAN: JavaScript URL Destruct juga wajib dipasangi skema parameter token role dinamis
        let url = "{{ route('management_posko.dapur_umum.destroy', ['role' => $userRole, 'dapur_umum' => ':id']) }}";
        url = url.replace(':id', id);

        document.getElementById('deleteForm').action = url;
    }

    function closeModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    </script>
@endsection