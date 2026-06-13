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

            <a href="{{ route('admin.management_posko.dapur_umum.create') }}"
                class="bg-indigo-700 text-white px-4 py-2 rounded-lg inline-block">
                + Tambah Data Dapur
            </a>

        </div>

        <form method="GET" action="{{ route('admin.management_posko.dapur_umum.index') }}">
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
                        <th class="p-4 text-center">Aksi</th>
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

                                    {{-- DETAIL KEBUTUHAN --}}
                                    <a href="{{ route('admin.management_posko.kebutuhan_harian.index', $item->id) }}"
                                        class="px-3 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">

                                        Detail Kebutuhan

                                    </a>

                                    {{-- EDIT --}}
                                    <a href="{{ route('admin.management_posko.dapur_umum.edit', $item->id) }}"
                                        class="px-3 py-2 bg-yellow-500 text-white rounded-lg text-sm hover:bg-yellow-600">
                                        Edit
                                    </a>

                                    {{-- DELETE --}}
                                    <form action="{{ route('admin.management_posko.dapur_umum.destroy', $item->id) }}"
                                        method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700">
                                            Hapus
                                        </button>
                                    </form>
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
                Menampilkan {{ $dapur->firstItem() }} - {{ $dapur->lastItem() }}
                dari {{ $dapur->total() }} data dapur umum
            </p>
            <div>
                {{ $dapur->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- MODAL HAPUS -->
    <div id="deleteModal" class="fixed inset-0 backdrop-blur-sm bg-white/10 hidden items-center justify-center z-50">

        <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">

            <div class="flex items-start gap-3">
                <div class="bg-red-100 p-2 rounded-full">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-500" />
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

            document.getElementById('namaDapur').innerText = `"${nama}"`;

            // route delete dapur
            let url = "{{ route('admin.management_posko.dapur_umum.destroy', ':id') }}";
            url = url.replace(':id', id);

            document.getElementById('deleteForm').action = url;
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
@endsection
