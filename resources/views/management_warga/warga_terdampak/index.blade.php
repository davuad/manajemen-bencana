@extends('layouts.app')

@section('title', 'Data Warga Terdampak')

@section('content')
    <div class="space-y-6">
        {{-- Breadcrumb --}}
        <div class="text-sm text-gray-500">
            Dashboard <span class="mx-1">&gt;</span> Data Warga Terdampak
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Card --}}
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            {{-- Header --}}
            <div
                class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h2 class="text-xl font-bold uppercase tracking-wide text-gray-900">
                        Data Warga Terdampak
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Data Warga Terdampak Wilayah Kabupaten Cilacap
                    </p>
                </div>

                <div>
                    <a href="{{ route('admin.warga.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-800">
                        <span>+</span>
                        Tambah Data Warga Terdampak
                    </a>
                </div>
            </div>

            {{-- Filter --}}
            <div class="border-b border-gray-100 px-6 py-5">
                <form action="{{ route('admin.warga.index') }}" method="GET"
                    class="grid grid-cols-1 gap-3 xl:grid-cols-12">
                    {{-- Search --}}
                    <div class="xl:col-span-4">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari berdasarkan No KK, Nama, NIK, atau Alamat"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                    </div>

                    {{-- Filter Desa --}}
                    <div class="xl:col-span-2">
                        <select name="desa"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                            <option value="">Semua Desa</option>
                            @foreach ($listDesa as $itemDesa)
                                <option value="{{ $itemDesa->id }}"
                                    {{ request('desa') == $itemDesa->id ? 'selected' : '' }}>
                                    {{ $itemDesa->nama_desa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Bencana (Diperbarui dari Kategori) --}}
                    <div class="xl:col-span-2">
                        <select name="bencana"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                            <option value="">Semua Bencana</option>
                            @foreach ($listBencana as $itemBencana)
                                <option value="{{ $itemBencana->id }}"
                                    {{ request('bencana') == $itemBencana->id ? 'selected' : '' }}>
                                    {{ $itemBencana->nama_bencana }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Status --}}
                    <div class="xl:col-span-2">
                        <select name="status_penyaluran"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                            <option value="">Semua Status</option>
                            @foreach ($listStatus as $status)
                                <option value="{{ $status }}"
                                    {{ request('status_penyaluran') == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="xl:col-span-2 flex flex-col gap-3 sm:flex-row xl:justify-end">
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-indigo-700 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-800 w-full">
                            Filter
                        </button>

                        <a href="{{ route('admin.warga.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 w-full">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">No.</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">No. KK</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">Nama Kepala Keluarga</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">Desa</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">Bencana</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">Jumlah Anggota</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">Jenis Bantuan</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">Status Penyaluran</th>
                            <th class="px-4 py-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($warga as $item)
                            <tr class="cursor-pointer transition hover:bg-gray-50"
                                data-url="{{ route('admin.warga.show', $item->id) }}">
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ ($warga->currentPage() - 1) * $warga->perPage() + $loop->iteration }}.
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $item->no_kk }}
                                </td>
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                    {{ $item->nama_kepala_keluarga }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $item->desa?->nama_desa }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{-- Diperbarui menjadi relasi bencana --}}
                                    {{ $item->bencana?->nama_bencana }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $item->jumlah_anggota }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $item->jenis_bantuan }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700" onclick="event.stopPropagation()">
                                    @if ($item->status_penyaluran == 'Belum diproses')
                                        <button type="button"
                                            class="status-btn inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-600 transition hover:bg-red-200"
                                            data-url="{{ route('admin.warga.ubahStatus', $item->id) }}"
                                            data-nama="{{ $item->nama_kepala_keluarga }}" data-next="Proses Penyaluran">
                                            Belum Diproses
                                        </button>
                                    @elseif ($item->status_penyaluran == 'Proses Penyaluran')
                                        <button type="button"
                                            class="status-btn inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-600 transition hover:bg-blue-200"
                                            data-url="{{ route('admin.warga.ubahStatus', $item->id) }}"
                                            data-nama="{{ $item->nama_kepala_keluarga }}" data-next="Sudah Disalurkan">
                                            Proses Penyaluran
                                        </button>
                                    @elseif ($item->status_penyaluran == 'Sudah disalurkan')
                                        <span
                                            class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-600">
                                            Sudah Disalurkan
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                            {{ $item->status_penyaluran }}
                                        </span>
                                    @endif
                                </td>
                                <td class="aksi-cell px-4 py-4 text-center" onclick="event.stopPropagation()">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.warga.edit', $item->id) }}"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-blue-600 transition hover:bg-blue-50"
                                            title="Edit">
                                            ✏️
                                        </a>

                                        <button type="button"
                                            class="delete-btn inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600 transition hover:bg-red-50"
                                            title="Hapus" data-url="{{ route('admin.warga.destroy', $item->id) }}"
                                            data-nama="{{ $item->nama_kepala_keluarga }}">
                                            🗑️
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-10 text-center text-sm text-gray-500">
                                    Data warga terdampak belum ada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div
                class="flex flex-col gap-4 border-t border-gray-100 px-6 py-4 text-sm text-gray-500 md:flex-row md:items-center md:justify-between">
                <div>
                    @if ($warga->total() > 0)
                        Menampilkan {{ $warga->firstItem() }}-{{ $warga->lastItem() }} dari {{ $warga->total() }} data
                    @else
                        Menampilkan 0 data
                    @endif
                </div>

                <div>
                    {{ $warga->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900">Hapus Data Warga Terdampak</h3>
                <button type="button" onclick="closeDeleteModal()"
                    class="text-2xl leading-none text-gray-400 hover:text-gray-600">
                    &times;
                </button>
            </div>

            <div class="px-6 py-5 text-sm leading-6 text-gray-600">
                Apakah Anda yakin ingin menghapus data warga atas nama
                <strong id="wargaNamaModal" class="text-gray-900"></strong>?
                Tindakan ini tidak dapat dibatalkan.
            </div>

            <div class="flex justify-end gap-3 px-6 pb-6">
                <button type="button" onclick="closeDeleteModal()"
                    class="rounded-xl border border-gray-200 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                    Batal
                </button>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="rounded-xl bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Status --}}
    <div id="statusModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900">Ubah Status Penyaluran</h3>
                <button type="button" onclick="closeStatusModal()"
                    class="text-2xl leading-none text-gray-400 hover:text-gray-600">
                    &times;
                </button>
            </div>

            <div class="px-6 py-5 text-sm leading-6 text-gray-600">
                Status penyaluran untuk warga
                <strong id="statusNamaModal" class="text-gray-900"></strong>
                akan diubah menjadi
                <strong id="statusNextModal" class="text-gray-900"></strong>.
            </div>

            <div class="flex justify-end gap-3 px-6 pb-6">
                <button type="button" onclick="closeStatusModal()"
                    class="rounded-xl border border-gray-200 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                    Batal
                </button>

                <form id="statusForm" method="POST">
                    @csrf
                    <button type="submit"
                        class="rounded-xl bg-indigo-700 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-800">
                        Ya, Ubah Data
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(url, namaWarga) {
            document.getElementById('deleteForm').setAttribute('action', url);
            document.getElementById('wargaNamaModal').textContent = "'" + namaWarga + "'";
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openStatusModal(url, namaWarga, statusBerikutnya) {
            document.getElementById('statusForm').setAttribute('action', url);
            document.getElementById('statusNamaModal').textContent = "'" + namaWarga + "'";
            document.getElementById('statusNextModal').textContent = statusBerikutnya;
            const modal = document.getElementById('statusModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeStatusModal() {
            const modal = document.getElementById('statusModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.querySelectorAll('tr[data-url]').forEach(function(row) {
            row.addEventListener('click', function(e) {
                if (e.target.closest('.aksi-cell') || e.target.closest('.status-btn')) return;
                window.location.href = row.dataset.url;
            });
        });

        document.querySelectorAll('.delete-btn').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                openDeleteModal(button.dataset.url, button.dataset.nama);
            });
        });

        document.querySelectorAll('.status-btn').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                openStatusModal(button.dataset.url, button.dataset.nama, button.dataset.next);
            });
        });

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        document.getElementById('statusModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeStatusModal();
            }
        });
    </script>
@endsection