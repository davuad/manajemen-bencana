@extends('layouts.app')

@section('content')
<div class="p-6 w-full">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold text-gray-800">Data Distribusi</h3>

        <a href="{{ route('management_distribusi.distribusi.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
            + Tambah
        </a>
    </div>


    <!-- Search -->
    <form method="GET" action="{{ route('management_distribusi.distribusi.index') }}">
        <div class="flex gap-4 mb-6">

            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari berdasarkan Nama Posko atau ID posko"
                class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">

        </div>
    </form>

    <!-- Table -->
    <div class="bg-white shadow rounded-xl p-4">
        <div class="overflow-x-auto">

            <table id="tableDistribusi" class="w-full text-sm text-left border">

                <thead class="bg-gray-100 text-xs uppercase">
                    <tr>
                        <th class="p-3">ID</th>
                        <th class="p-3">Bencana</th>
                        <th class="p-3">Posko</th>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Lokasi</th>
                        <th class="p-3">Kendaraan</th>
                        <th class="p-3">Supir</th>
                        <th class="p-3">No Kendaraan</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($distribusi as $item)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3">{{ $item->id }}</td>
                        <td class="p-3">{{ $item->bencana->id ?? '-' }}</td>
                        <td class="p-3">{{ $item->posko->nama_posko ?? '-' }}</td>
                        <td class="p-3">{{ $item->tanggal_distribusi }}</td>
                        <td class="p-3">{{ $item->lokasi_distribusi }}</td>
                        <td class="p-3">{{ $item->kendaraan }}</td>
                        <td class="p-3">{{ $item->nama_supir }}</td>
                        <td class="p-3">{{ $item->nomor_kendaraan }}</td>
                        <td class="p-3">{{ $item->kategori_distribusi }}</td>

                        <!-- STATUS -->
                        <td class="p-3">
                            <span class="px-2 py-1 text-xs rounded
                                {{ $item->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $item->status == 'dikirim' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $item->status == 'selesai' ? 'bg-green-100 text-green-700' : '' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>

                        <!-- AKSI -->
                        <td class="p-3">
                            <div class="flex gap-2 justify-center">

                                <!-- DETAIL -->
                                <a href="{{ route('management_distribusi.distribusi.show', $item->id) }}"
                                   class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                      <x-heroicon-o-eye class="w-5 h-5" />
                                </a>

                                <!-- EDIT -->
                                <a href="{{ route('management_distribusi.distribusi.edit', $item->id) }}"
                                   class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">
                                     <x-heroicon-o-pencil-square class="w-5 h-5" />
                                </a>
                                    <button 
    onclick="openModal('{{ $item->id }}')" 
    class="text-red-500 hover:text-red-700">
    <x-heroicon-o-trash class="w-5 h-5" />
</button>
                        
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center p-4 text-gray-500">
                            Tidak ada data
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>
    
</div>
<div id="deleteModal" class="fixed inset-0 backdrop-blur-sm bg-white/10 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">

        <!-- Header -->
        <div class="flex items-start gap-3">
            <div class="bg-red-100 p-2 rounded-full">
                <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-500"/>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-gray-800">Hapus Data Distribusi</h2>
<p class="text-sm text-gray-500 mt-1">
    Apakah Anda yakin ingin menghapus data distribusi ID 
    <span id="deleteId" class="font-semibold"></span>? 
    Tindakan ini tidak dapat dibatalkan.
</p>
            </div>
        </div>

        <!-- Action -->
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
                    Hapus Data
                </button>
            </form>
        </div>

    </div>
</div>
<!-- Search Script -->
<script>
document.getElementById('search').addEventListener('keydown', e => {
    if (e.key === 'Enter') {
        let val = e.target.value.toLowerCase();

        document.querySelectorAll("#tableDistribusi tbody tr").forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(val) ? "" : "none";
        });
    }
});
</script>
<script>
function openModal(id) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    const text = document.getElementById('deleteId');

    // tampilkan ID
    text.innerText = id;

    // set action form (INI PENTING)
    form.action = "{{ url('management-distribusi/distribusi') }}/" + id;

    // munculin modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}
</script>
@endsection
