@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <div>
            <h2 class="text-xl font-bold">Olah Data Dapur Umum</h2>
            <p class="text-gray-500 text-sm">
                Kelola data dapur umum untuk kebutuhan logistik warga
            </p>
        </div>

        <a href="{{ route('management_posko.dapur_umum.create') }}" 
           class="bg-indigo-700 text-white px-4 py-2 rounded-lg inline-block">
            + Tambah Data Dapur
        </a>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-center">No</th>
                    <th class="text-center">Nama Posko</th>
                    <th class="text-center">Nama Dapur Umum</th>
                    <th class="text-center">Kapasitas</th>
                    <th class="text-center">Jumlah Warga</th>
                    <th class="text-center">Penanggung Jawab</th>
                    <th class="text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($dapur as $key => $d)
                <tr class="border-t">
                    <td class="p-2 text-center">{{ $key + 1 }}</td>
                    <td class="p-2 text-center">{{ $d->posko->nama_posko ?? '-' }}</td>
                    <td class="p-2 text-center">{{ $d->nama_dapur_umum }}</td>
                    <td class="p-2 text-center">{{ $d->kapasitas_warga }}</td>
                    <td class="p-2 text-center">{{ $d->jumlah_warga }}</td>
                    <td class="p-2 text-center">{{ $d->penanggung_jawab }}</td>

                    <td class="flex gap-1 py-3">
                        <!-- EDIT -->
                        <a href="{{ route('management_posko.dapur_umum.edit', $d->id) }}"
                           class="text-blue-500">
                            <x-heroicon-o-pencil-square class="w-5 h-5" />
                        </a>

                        <!-- DELETE -->
                        <button 
                            onclick="openModal('{{ $d->id }}', '{{ $d->nama_dapur_umum }}')" 
                            class="text-red-500 hover:text-red-700">
                            <x-heroicon-o-trash class="w-5 h-5" />
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center p-4">
                        Data dapur umum belum ada
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>

<!-- MODAL HAPUS -->
<div id="deleteModal" class="fixed inset-0 backdrop-blur-sm bg-white/10 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">

        <div class="flex items-start gap-3">
            <div class="bg-red-100 p-2 rounded-full">
                <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-500"/>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-gray-800">Hapus Data Dapur</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Yakin ingin menghapus data dapur  
                    <span id="namaDapur" class="font-semibold"></span>?
                </p>
            </div>
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

    document.getElementById('namaDapur').innerText = `"${nama}"`;

    // route delete dapur
    document.getElementById('deleteForm').action = `/dapur-umum/${id}`;
}

function closeModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>

@endsection