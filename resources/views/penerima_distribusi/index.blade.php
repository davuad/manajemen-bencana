@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold">Data Penerima</h2>
            <p class="text-gray-500 text-sm">
                Kelola data penerima distribusi posko
            </p>
        </div>

        <a href="{{ route('penerima.create') }}" 
           class="bg-indigo-700 text-white px-4 py-2 rounded-lg">
            + Tambah Penerima
        </a>
    </div>

    <form method="GET" action="{{ url()->current() }}">
<div class="flex gap-4 mb-6">

    <input type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Cari berdasarkan nama penerima distribusi posko"
        class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">

    <select name="status" class="border rounded-lg py-2">
        <option value="">Semua Status</option>
        <option value="Tidak Aktif"
            {{ request('status') == 'Tidak Aktif' ? 'selected' : '' }}>
            Tidak Aktif
        </option>

        <option value="Aktif"
            {{ request('status') == 'Aktif' ? 'selected' : '' }}>
            Aktif
        </option>
    </select>

    <button class="bg-indigo-600 text-white px-4 rounded-lg">
        Cari
    </button>

</div>
</form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-center">No</th>
                    <!-- <th class="text-center">ID Distribusi</th> -->
                    <th class="text-center">Nama</th>
                    <th class="text-center">Jabatan</th>
                    <th class="text-center">Instansi</th>
                    <th class="text-center">Alamat</th>
                    <th class="text-center">No HP</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Posko</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $key => $item)
                <tr class="border-t">
                    <td class="p-2 text-center">{{ $key + 1 }}</td>
                    <!-- <td class="p-2 text-center">{{ $item->detail_distribusi_id }}</td> -->
                    <td class="p-2 text-center">{{ $item->nama_penerima }}</td>
                    <td class="p-2 text-center">{{ $item->jabatan }}</td>
                    <td class="p-2 text-center">{{ $item->instansi }}</td>
                    <td class="p-2 text-center">{{ $item->alamat }}</td>
                    <td class="p-2 text-center">{{ $item->no_hp }}</td>
                    <td class="p-2 text-center">{{ $item->status }}</td>
                    <td class="p-2 text-center">
                        @php
                            $posko = [
                                1 => 'Posko Cilacap Tengah 1',
                                2 => 'Posko Cilacap Selatan 1',
                                3 => 'Posko Cilacap Tengah 2',
                                4 => 'Posko Cilacap Selatan 2'
                            ];
                        @endphp

                        {{ $posko[$item->nama_posko] ?? '-' }}
                    </td>

                    <td class="flex justify-center gap-3 py-3">
    
                        <!-- EDIT -->
                        <a href="{{ route('penerima.edit', $item->penerima_id) }}"
                        class="text-blue-500 hover:text-blue-700">
                            <x-heroicon-o-pencil class="w-5 h-5"/>
                        </a>

                        <!-- DELETE -->
                        <button 
                            onclick="openModal('{{ $item->penerima_id }}', '{{ $item->nama_penerima }}')" 
                            class="text-red-500 hover:text-red-700">
                            <x-heroicon-o-trash class="w-5 h-5"/>
                        </button>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center p-4">
                        @if(request('search'))
                            Data tidak ditemukan
                        @else
                            Data penerima belum ada
                        @endif
                    </td>
                </tr>
                @endforelse
                <script>
                const searchInput = document.querySelector('input[name="search"]');

                searchInput.addEventListener('input', function() {
                    if (this.value === '') {
                        // kalau kosong, reload tanpa parameter (balik ke semua data)
                        window.location.href = window.location.pathname;
                    }
                });

                let timeout = null;

                searchInput.addEventListener('input', function() {
                    clearTimeout(timeout);

                    timeout = setTimeout(() => {
                        this.form.submit();
                    }, 500); // delay 0.5 detik
                });

                </script>
            </tbody>
        </table>
    </div>

</div>

<!-- MODAL HAPUS -->
<div id="deleteModal" class="fixed inset-0 backdrop-blur-sm bg-white/10 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">

        <div>
            <h2 class="text-lg font-semibold text-gray-800">Hapus Data</h2>
            <p class="text-sm text-gray-500 mt-1">
                Yakin ingin menghapus data  
                <span id="namaData" class="font-semibold"></span>?
            </p>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <button onclick="closeModal()" 
                class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">
                Batal
            </button>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" 
                    class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600">
                    Hapus
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

    document.getElementById('namaData').innerText = `"${nama}"`;

    let url = "{{ route('penerima.destroy', ':id') }}";
    url = url.replace(':id', id);

    document.getElementById('deleteForm').action = url;
}

function closeModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>

@endsection