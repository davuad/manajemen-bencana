@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">

        {{-- Header --}}
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-xl font-bold">Detail Paket Bantuan</h2>
                <p class="text-gray-500 text-sm">
                    Kelola data detail paket bantuan pasca bencana wilayah kabupaten Cilacap
                </p>
            </div>

                <a href="{{ route('admin.management_distribusi.paket_bantuan.index') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg">
                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                    Kembali
                </a>

        </div>

        {{-- Card Paket Bantuan --}}
        <div class="border rounded-xl p-6 mb-8">
            <h3 class="text-xl font-bold mb-4">Data Paket Bantuan</h3>
            <hr class="mb-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                <div class="font-semibold">Nama Paket Bantuan</div>
                <div>{{ $paket_bantuan->nama_paket }}</div>

                <div class="font-semibold">Keterangan</div>
                <div>{{ $paket_bantuan->keterangan ?? '-' }}</div>

                <div class="font-semibold">Status Paket</div>
                <div>
                    @if ($paket_bantuan->status == 'aktif')
                        <span class="inline-block px-4 py-2 rounded-full bg-green-200 text-green-800 font-semibold">
                            Aktif
                        </span>
                    @else
                        <span class="inline-block px-4 py-2 rounded-full bg-red-200 text-red-700 font-semibold opacity-70">
                            Non Aktif
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div id="alertBox"
                class="mb-4 flex justify-between items-center p-4 rounded-lg bg-green-100 text-green-800 border border-green-300">
                <span>{{ session('success') }}</span>
                <button onclick="document.getElementById('alertBox').remove()">✕</button>
            </div>
        @endif

        {{-- Header tabel --}}
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Daftar Detail Isi Paket Bantuan</h2>

            <div class="flex gap-3 items-center">
                <form method="GET" action="{{ route('admin.management_distribusi.detail_paket.index') }}" class="flex gap-2">
                    <input type="hidden" name="paket_bantuan_id" value="{{ $paket_bantuan->id }}">

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari Berdasarkan Nama Barang" class="border rounded-lg px-4 py-2 w-64">

                    <button type="submit" class="hidden">Cari</button>
                </form>

                <a href="{{ route('admin.management_distribusi.detail_paket.create', ['paket_bantuan_id' => $paket_bantuan->id]) }}"
                    class="bg-indigo-800 hover:bg-indigo-900 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
                    <span class="text-lg font-bold">+</span>
                    Tambah Barang
                </a>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-4 text-left">No.</th>
                        <th class="p-4 text-left">Nama Barang</th>
                        <th class="p-4 text-left">Jumlah</th>
                        <th class="p-4 text-left">Satuan</th>
                        <th class="p-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detail_paket as $key => $item)
                        <tr class="border-b">
                            <td class="p-4">{{ $detail_paket->firstItem() + $key }}.</td>
                            <td class="p-4">{{ $item->barang->nama_barang ?? '-' }}</td>
                            <td class="p-4">{{ $item->jumlah }}</td>
                            <td class="p-4">{{ $item->barang->satuan ?? '-' }}</td>
                            <td class="p-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.management_distribusi.detail_paket.edit', $item->id) }}"
                                        class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-2 rounded-lg inline-flex items-center gap-1">
                                        <x-heroicon-o-pencil-square class="w-4 h-4" />
                                        Edit
                                    </a>

                                    <button
                                        onclick="openModal('{{ $item->id }}', '{{ $item->barang->nama_barang ?? 'Data' }}')"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg inline-flex items-center gap-1">
                                        <x-heroicon-o-trash class="w-4 h-4" />
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center">Data detail paket belum ada</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer pagination --}}
        <div class="flex justify-between items-center mt-4 text-sm text-gray-500">
            <p>
                Menampilkan {{ $detail_paket->firstItem() ?? 0 }}-{{ $detail_paket->lastItem() ?? 0 }}
                dari {{ $detail_paket->total() }} data
            </p>
            <div>
                {{ $detail_paket->withQueryString()->links() }}
            </div>
        </div>
    </div>

    {{-- Modal delete --}}
    <div id="deleteModal" class="fixed inset-0 backdrop-blur-sm bg-white/10 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">
            <div class="flex items-start gap-3">
                <div class="bg-red-100 p-2 rounded-full">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-500" />
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Hapus Detail Paket</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Apakah Anda yakin ingin menghapus barang
                        <span id="namaBarang" class="font-semibold"></span>?
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

            document.getElementById('namaBarang').innerText = `"${nama}"`;

            let url = "{{ route('admin.management_distribusi.detail_paket.destroy', ':id') }}";
            url = url.replace(':id', id);

            document.getElementById('deleteForm').action = url;
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        setTimeout(() => {
            let alert = document.getElementById('alertBox');
            if (alert) alert.remove();
        }, 3000);
    </script>
@endsection
