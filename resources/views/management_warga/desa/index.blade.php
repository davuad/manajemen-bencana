@extends('layouts.app')

@section('title', 'Data Desa')

@section('content')
    <div class="space-y-6">
        {{-- Breadcrumb --}}
        <div class="text-sm text-gray-500">
            Dashboard <span class="mx-1">&gt;</span> Data Desa
        </div>

        {{-- Alert --}}
        @if(session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Card --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-gray-100">
            {{-- Header --}}
            <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h2 class="text-xl font-bold uppercase tracking-wide text-gray-900">
                        Data Desa
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Data Desa Wilayah Kabupaten Cilacap
                    </p>
                </div>

                <div>
                    <a href="{{ route('desa.create') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-800">
                        <span>+</span>
                        Tambah Data Desa
                    </a>
                </div>
            </div>

            {{-- Filter --}}
            <div class="border-b border-gray-100 px-6 py-5">
                <form action="{{ route('desa.index') }}" method="GET" class="grid grid-cols-1 gap-3 xl:grid-cols-12">
                    {{-- Search --}}
                    <div class="xl:col-span-5">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari berdasarkan Nama, Kecamatan, atau Kepala Desa"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100"
                        >
                    </div>

                    {{-- Filter Desa --}}
                    <div class="xl:col-span-2">
                        <select
                            name="desa"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100"
                        >
                            <option value="">Semua Desa</option>
                            @foreach ($listDesa as $namaDesa)
                                <option value="{{ $namaDesa }}" {{ request('desa') == $namaDesa ? 'selected' : '' }}>
                                    {{ $namaDesa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Kecamatan --}}
                    <div class="xl:col-span-2">
                        <select
                            name="kecamatan"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100"
                        >
                            <option value="">Semua Kecamatan</option>
                            @foreach ($listKecamatan as $namaKecamatan)
                                <option value="{{ $namaKecamatan }}" {{ request('kecamatan') == $namaKecamatan ? 'selected' : '' }}>
                                    {{ $namaKecamatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="xl:col-span-3 flex flex-col gap-3 sm:flex-row xl:justify-end">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-indigo-700 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-800"
                        >
                            Filter
                        </button>

                        <a href="{{ route('desa.index') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
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
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">No.</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama Desa</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Kecamatan</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama Kepala Desa</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Kontak Kepala Desa</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($desa as $item)
                            <tr
                                class="cursor-pointer transition hover:bg-gray-50"
                                data-url="{{ route('desa.detail', $item->id) }}"
                            >
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ ($desa->currentPage() - 1) * $desa->perPage() + $loop->iteration }}.
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $item->nama_desa }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $item->kecamatan }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $item->nama_kades }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $item->kontak_kades }}
                                </td>
                                <td class="aksi-cell px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('desa.edit', $item->id) }}"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-blue-600 transition hover:bg-blue-50"
                                           title="Edit">
                                            ✏️
                                        </a>

                                        <button
                                            type="button"
                                            class="delete-btn inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600 transition hover:bg-red-50"
                                            title="Hapus"
                                            data-url="{{ route('desa.delete', $item->id) }}"
                                            data-nama="{{ $item->nama_desa }}"
                                        >
                                            🗑️
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">
                                    Data desa belum ada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="flex flex-col gap-4 border-t border-gray-100 px-6 py-4 text-sm text-gray-500 md:flex-row md:items-center md:justify-between">
                <div>
                    @if ($desa->total() > 0)
                        Menampilkan {{ $desa->firstItem() }}-{{ $desa->lastItem() }} dari {{ $desa->total() }} data
                    @else
                        Menampilkan 0 data
                    @endif
                </div>

                <div>
                    {{ $desa->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900">Hapus Data Desa</h3>
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="text-2xl leading-none text-gray-400 hover:text-gray-600">
                    &times;
                </button>
            </div>

            <div class="px-6 py-5 text-sm leading-6 text-gray-600">
                Apakah Anda yakin ingin menghapus data desa
                <strong id="desaNamaModal" class="text-gray-900"></strong>?
                Tindakan ini tidak dapat dibatalkan dan semua data terkait akan ikut terhapus.
            </div>

            <div class="flex justify-end gap-3 px-6 pb-6">
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="rounded-xl border border-gray-200 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                    Batal
                </button>

                <form id="deleteForm" method="GET">
                    <button type="submit"
                            class="rounded-xl bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(url, namaDesa) {
            document.getElementById('deleteForm').setAttribute('action', url);
            document.getElementById('desaNamaModal').textContent = "'" + namaDesa + "'";
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.querySelectorAll('tr[data-url]').forEach(function(row) {
            row.addEventListener('click', function(e) {
                if (e.target.closest('.aksi-cell')) return;
                window.location.href = row.dataset.url;
            });
        });

        document.querySelectorAll('.delete-btn').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                openDeleteModal(button.dataset.url, button.dataset.nama);
            });
        });

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
@endsection