@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-xl font-bold">Data Bencana Kabupaten Cilacap</h2>
                <p class="text-gray-500 text-sm">
                    Informasi kejadian bencana berdasarkan laporan pengaduan
                </p>
            </div>
        </div>

        <div class="border-b pb-3 mb-4"></div>

        {{-- FILTER --}}
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid md:grid-cols-5 gap-3 mb-5">

            {{-- TANGGAL MULAI --}}
            <div>
                <label class="text-xs text-gray-500">Tanggal Awal</label>
                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                    class="border rounded px-3 py-2 w-full">
            </div>

            {{-- TANGGAL SELESAI --}}
            <div>
                <label class="text-xs text-gray-500">Tanggal Akhir</label>
                <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                    class="border rounded px-3 py-2 w-full">
            </div>

            {{-- SEARCH DESA --}}
            <div>
                <label class="text-xs text-gray-500">Nama Desa</label>
                <input type="text" name="search" placeholder="Cari nama desa..." value="{{ request('search') }}"
                    class="border rounded px-3 py-2 w-full">
            </div>

            {{-- STATUS --}}
            <div>
                <label class="text-xs text-gray-500">Status Penanganan</label>
                <select name="status" class="border rounded px-3 py-2 w-full">
                    <option value="">Semua Status</option>

                    <option value="BELUM_DITANGANI" {{ request('status') == 'BELUM_DITANGANI' ? 'selected' : '' }}>
                        BELUM DITANGANI
                    </option>

                    <option value="DITANGANI" {{ request('status') == 'DITANGANI' ? 'selected' : '' }}>
                        DITANGANI
                    </option>

                    <option value="SELESAI" {{ request('status') == 'SELESAI' ? 'selected' : '' }}>
                        SELESAI
                    </option>

                    <option value="TIDAK_DIREKOMENDASIKAN"
                        {{ request('status') == 'TIDAK_DIREKOMENDASIKAN' ? 'selected' : '' }}>
                        TIDAK DIREKOMENDASIKAN
                    </option>
                </select>
            </div>

            {{-- BUTTON --}}
            <div class="flex items-end gap-2">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white rounded px-4 py-2 transition w-full">
                    Filter
                </button>

                @if (request()->hasAny(['tanggal_mulai', 'tanggal_selesai', 'search', 'status']))
                    <a href="{{ route('admin.laporan.index') }}"
                        class="bg-gray-400 hover:bg-gray-500 text-white rounded px-4 py-2 text-center transition w-full">
                        Reset
                    </a>
                @endif
            </div>

        </form>

        {{-- DOWNLOAD PDF --}}
        <div class="mb-4 flex justify-end">
            <a href="{{ route('admin.laporan.pdf', request()->query()) }}"
                class="flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <polyline points="7 10 12 15 17 10" />
                    <line x1="12" y1="15" x2="12" y2="3" />
                </svg>

                <span>Unduh PDF</span>
            </a>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-center">No</th>
                        <th class="p-3 text-center">Tanggal</th>
                        <th class="p-3 text-left">Nama Bencana</th>
                        <th class="p-3 text-left">Kategori</th>
                        <th class="p-3 text-left">Desa</th>
                        <th class="p-3 text-center">Tingkat Kerusakan</th>
                        <th class="p-3 text-center">Status</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($laporan as $key => $b)
                        <tr class="border-t hover:bg-gray-50 transition">

                            <td class="p-2 text-center">
                                {{ $laporan->firstItem() + $key }}
                            </td>

                            <td class="p-2 text-center">
                                {{ \Carbon\Carbon::parse($b->tanggal)->format('d-m-Y') }}
                            </td>

                            <td class="p-2">
                                {{ $b->nama_bencana }}
                            </td>

                            <td class="p-2">
                                {{ $b->kategori->nama_kategori ?? '-' }}
                            </td>

                            <td class="p-2">
                                {{ $b->desa->nama_desa ?? '-' }}
                            </td>

                            <td class="p-2 text-center">
                                {{ $b->tingkat_kerusakan }}
                            </td>

                            <td class="p-2 text-center">
                                <span class="px-2 py-1 text-xs font-semibold border rounded">
                                    {{ str_replace('_', ' ', $b->pengaduan->status_pengaduan ?? '-') }}
                                </span>
                            </td>

                            {{-- ACTION --}}
                            <td class="p-2 text-center">
                                <a href="{{ route('admin.laporan.pdf.detail', $b->id) }}" target="_blank"
                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded transition">
                                    Lihat Detail
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-4 text-gray-500">
                                Data belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="mt-4">
            <p class="text-sm text-gray-700">
                Total bencana:
                <span class="font-bold">{{ $laporan->total() }}</span>
            </p>
        </div>

        {{-- PAGINATION --}}
        <div class="flex justify-between items-center mt-6 text-sm">

            <p class="text-gray-500">
                Menampilkan {{ $laporan->firstItem() ?? 0 }}
                - {{ $laporan->lastItem() ?? 0 }}
                dari {{ $laporan->total() }} data
            </p>

            <div>
                {{ $laporan->withQueryString()->links() }}
            </div>

        </div>

    </div>
@endsection
