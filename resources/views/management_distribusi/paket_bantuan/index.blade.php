@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">

        <div class="flex justify-between items-center mb-6">

            <div>
                <h2 class="text-xl font-bold">Olah Data Paket Bantuan</h2>
                <p class="text-gray-500 text-sm">
                    Kelola data paket bantuan untuk distribusi
                </p>
            </div>

            <a href="{{ route('admin.management_distribusi.paket_bantuan.create') }}"
                class="bg-indigo-700 text-white px-4 py-2 rounded-lg inline-block">
                + Tambah Paket Bantuan
            </a>

        </div>

        {{-- FILTER --}}
        <form method="GET" action="{{ route('admin.management_distribusi.paket_bantuan.index') }}">
            <div class="flex gap-4 mb-6">

                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari berdasarkan Nama Paket"
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
        @if (session('success'))
            <div id="alertBox"
                class="mb-4 flex justify-between items-center p-4 rounded-lg bg-green-100 text-green-800 border border-green-300">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-check-circle class="w-5 h-5 text-green-600" />
                    <span>{{ session('success') }}</span>
                </div>

                <button onclick="document.getElementById('alertBox').remove()" class="text-green-700">
                    ✕
                </button>
            </div>
        @endif

        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-center">No</th>
                        <th class="text-center">Nama Paket</th>
                        <th class="text-center">Posko</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($paket_bantuan as $key => $p)
                        <tr class="border-t">
                            <td class="p-2 text-center">{{ $key + 1 }}</td>
                            <td class="p-2 pl-4">{{ $p->nama_paket }}</td>
                            <td class="p-2 pl-4">{{ $p->posko->nama_posko ?? '-' }}</td>
                            <td class="p-2 pl-4">{{ $p->keterangan ?? '-' }}</td>

                            <td class="p-2 text-center">
                                @if ($p->status == 'aktif')
                                    <span class="inline-block px-4 py-2 rounded-full bg-green-200 text-green-800 font-semibold">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-block px-4 py-2 rounded-full bg-red-200 text-red-700 font-semibold opacity-70">
                                        Non Aktif
                                    </span>
                                @endif
                            </td>

                            <td class="flex gap-2 py-4">
                                <a href="{{ route('admin.management_distribusi.detail_paket.index', ['paket_bantuan_id' => $p->id]) }}"
                                    class="text-green-600">
                                    <x-heroicon-o-clipboard-document-list class="w-5 h-5" />
                                </a>
                                
                                <a href="{{ route('admin.management_distribusi.paket_bantuan.edit', $p->id) }}"
                                    class="text-blue-500">
                                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                                </a>

                                <button onclick="openModal('{{ $p->id }}', '{{ $p->nama_paket }}')"
                                    class="text-red-500">
                                    <x-heroicon-o-trash class="w-5 h-5" />
                                </button>
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

        {{-- PAGINATION --}}
        <div class="flex justify-between items-center mt-6 text-sm">

            <p class="text-gray-500">
                Menampilkan {{ $paket_bantuan->firstItem() }} - {{ $paket_bantuan->lastItem() }}
                dari {{ $paket_bantuan->total() }} data
            </p>

            <div>
                {{ $paket_bantuan->withQueryString()->links() }}
            </div>

        </div>

    </div>

    {{-- MODAL DELETE --}}
    <div id="deleteModal" class="fixed inset-0 backdrop-blur-sm bg-white/10 hidden items-center justify-center z-50">

        <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">

            <div class="flex items-start gap-3">
                <div class="bg-red-100 p-2 rounded-full">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-500" />
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Hapus Paket Bantuan</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Apakah Anda yakin ingin menghapus paket
                        <span id="namaPaket" class="font-semibold"></span>?
                    </p>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button onclick="closeModal()" class="px-4 py-2 rounded-lg bg-gray-200">
                    Batal
                </button>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-500 text-white">
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

            document.getElementById('namaPaket').innerText = `"${nama}"`;

            let url = "{{ route('admin.management_distribusi.paket_bantuan.destroy', ':id') }}";
            url = url.replace(':id', id);

            document.getElementById('deleteForm').action = url;
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
@endsection
