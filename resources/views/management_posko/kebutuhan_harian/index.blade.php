@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <div>
            <h2 class="text-xl font-bold">Olah Data Kebutuhan Harian</h2>
            <p class="text-gray-500 text-sm">
                Kelola kebutuhan konsumsi harian dapur umum
            </p>
        </div>

        <a href="{{ route('management_posko.kebutuhan_harian.create') }}" 
           class="bg-indigo-700 text-white px-4 py-2 rounded-lg inline-block">
            + Tambah Data
        </a>

    </div>

    <form method="GET" action="{{ route('management_posko.kebutuhan_harian.index') }}">
        <div class="flex gap-4 mb-6">

            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari berdasarkan tanggal atau dapur"
                class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">

            <select name="posko" class="border rounded-lg py-2">
                <option value="">Semua Dapur</option>
                @foreach($dapur as $d)
                    <option value="{{ $d->id }}" {{ request('dapur_umum') == $d->id ? 'selected' : '' }}>
                        {{ $d->nama_dapur_umum }}
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
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Dapur</th>
                    <th class="text-center">Jumlah Warga</th>
                    <th class="text-center">Porsi / Orang dalam sehari</th>
                    <th class="text-center">Total Porsi</th>
                    <th class="text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($kebutuhan as $key => $k)
                <tr class="border-t">
                    <td class="p-2 text-center">{{ $key + 1 }}</td>
                    <td class="p-2 text-center">{{ $k->tanggal }}</td>
                    <td class="p-2 text-center">{{ $k->dapur_umum->nama_dapur_umum ?? '-' }}</td>
                    <td class="p-2 text-center">{{ $k->jumlah_warga }}</td>
                    <td class="p-2 text-center">{{ $k->porsi_per_orang }}</td>
                    <td class="p-2 text-center">{{ $k->total_porsi }}</td>

                    <td class="flex gap-1 py-3">
                        <a href="{{ route('management_posko.kebutuhan_harian.edit', $k->id) }}"
                           class="text-blue-500">
                            <x-heroicon-o-pencil-square class="w-5 h-5" />
                        </a>

                        <button 
                            onclick="openModal('{{ $k->id }}', '{{ $k->tanggal }}')" 
                            class="text-red-500 hover:text-red-700">
                            <x-heroicon-o-trash class="w-5 h-5" />
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center p-4">
                        Data belum ada
                    </td>
                </tr>
                @endforelse

            </tbody>

        </table>

    </div>

    <div class="flex justify-between items-center mt-6 text-sm">
        <p class="text-gray-500">
            Menampilkan {{ $kebutuhan->firstItem() }} - {{ $kebutuhan->lastItem() }} 
            dari {{ $kebutuhan->total() }} data kebutuhan harian
        </p>

        <div>
            {{ $kebutuhan->withQueryString()->links() }}
        </div>

    </div>

</div>

<!-- MODAL HAPUS -->
<div id="deleteModal" class="fixed inset-0 backdrop-blur-sm bg-white/10 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">

        <!-- Header -->
        <div class="flex items-start gap-3">
            <div class="bg-red-100 p-2 rounded-full">
                <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-500"/>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-gray-800">Hapus Data</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Apakah Anda yakin ingin menghapus data tanggal 
                    <span id="namaData" class="font-semibold"></span>? 
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


<script>
function openModal(id, nama) {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    document.getElementById('namaData').innerText = `"${nama}"`;

    let url = "{{ route('management_posko.kebutuhan_harian.destroy', ':id') }}";
    url = url.replace(':id', id);

    document.getElementById('deleteForm').action = url;
}

function closeModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>

@endsection