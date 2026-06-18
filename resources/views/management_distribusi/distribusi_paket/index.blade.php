@extends('layouts.app')

@section('content')
    <div class="space-y-8">

        {{-- Header --}}
        <div>
            <h1 class="text-xl font-bold">Distribusi Paket Bantuan</h1>
            <p class="text-sm text-gray-500">Kelola calon penerima bantuan dan riwayat distribusi paket.</p>
        </div>

        {{-- FILTER --}}
        <div class="bg-white rounded-xl shadow p-6">


            <form method="GET" action="{{ route('admin.management_distribusi.distribusi_paket.index') }}">
                <div class="flex flex-wrap gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. KK / Nama..."
                        class="flex-1 min-w-[250px] border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">

                    <select name="desa_id"
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Desa</option>
                        @foreach ($desaList as $desa)
                            <option value="{{ $desa->id }}" {{ request('desa_id') == $desa->id ? 'selected' : '' }}>
                                {{ $desa->nama_desa }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Filter
                    </button>

                    <a href="{{ route('admin.management_distribusi.distribusi_paket.index') }}"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div id="alertSuccess"
                class="mb-4 flex justify-between items-center p-4 rounded-lg bg-green-100 text-green-800 border border-green-300">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-check-circle class="w-5 h-5 text-green-600" />
                    <span>{{ session('success') }}</span>
                </div>

                <button onclick="document.getElementById('alertSuccess').remove()" class="text-green-700">
                    ✕
                </button>
            </div>
        @endif

        @if (session('error'))
            <div id="alertError"
                class="mb-4 flex justify-between items-center p-4 rounded-lg bg-red-100 text-red-800 border border-red-300">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-x-circle class="w-5 h-5 text-red-600" />
                    <span>{{ session('error') }}</span>
                </div>

                <button onclick="document.getElementById('alertError').remove()" class="text-red-700">
                    ✕
                </button>
            </div>
        @endif

        {{-- TABEL WARGA CALON PENERIMA --}}
        <div class="bg-white rounded-xl shadow p-6">
            <div class="mb-6">
                <h2 class="text-xl font-bold">Daftar Warga Penerima Bantuan</h2>
                <p class="text-gray-500 text-sm">
                    Kelola calon penerima bantuan yang siap diproses untuk distribusi.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[1100px]">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-center">No</th>
                            <th class="text-center">No. KK</th>
                            <th class="text-center">Nama Kepala Keluarga</th>
                            <th class="text-center">Desa</th>
                            <th class="text-center">Bencana</th>
                            <th class="text-center">Jumlah Anggota</th>
                            <th class="text-center">Status Distribusi</th>
                            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('pegawai'))
                                <th class="text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($warga as $item)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-3 text-center">
                                    {{ ($warga->currentPage() - 1) * $warga->perPage() + $loop->iteration }}
                                </td>
                                <td class="p-3 pl-4">{{ $item->no_kk }}</td>
                                <td class="p-3 pl-4">{{ $item->nama_kepala_keluarga }}</td>
                                <td class="p-3 pl-4">{{ $item->desa->nama_desa ?? '-' }}</td>
                                <td class="p-3 pl-4">{{ $item->bencana->nama_bencana ?? '-' }}</td>
                                <td class="p-3 text-center">{{ $item->jumlah_anggota }}</td>

                                <td class="p-3 text-center">
                                    @if ($item->status_penyaluran == 'Belum diproses')
                                        <span
                                            class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 font-semibold text-xs">
                                            {{ $item->status_penyaluran }}
                                        </span>
                                    @elseif($item->status_penyaluran == 'Proses Penyaluran')
                                        <span
                                            class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-semibold text-xs">
                                            {{ $item->status_penyaluran }}
                                        </span>
                                    @elseif($item->status_penyaluran == 'Sudah disalurkan')
                                        <span
                                            class="px-3 py-1 rounded-full bg-green-100 text-green-700 font-semibold text-xs">
                                            {{ $item->status_penyaluran }}
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 font-semibold text-xs">
                                            {{ $item->status_penyaluran }}
                                        </span>
                                    @endif
                                </td>

                                @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('pegawai'))
                                    <td class="p-3 text-center">

                                        @if ($item->status_penyaluran == 'Belum diproses')
                                            <a href="{{ route('admin.management_distribusi.distribusi_paket.create', ['warga_id' => $item->id]) }}"
                                                class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-lg text-xs hover:bg-slate-700">
                                                <x-heroicon-o-truck class="w-4 h-4" />
                                                <span>Distribusi Bantuan</span>
                                            </a>
                                        @elseif ($item->status_penyaluran == 'Proses Penyaluran')
                                            <button type="button"
                                                class="bg-gray-300 text-gray-600 px-4 py-2 rounded-lg text-xs cursor-not-allowed"
                                                disabled>
                                                Tidak Tersedia
                                            </button>
                                        @endif

                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center p-4 text-gray-500">
                                    Tidak ada warga yang memenuhi syarat distribusi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Warga --}}
            <div class="flex justify-between items-center mt-6 text-sm">
                <p class="text-gray-500">
                    Menampilkan {{ $warga->firstItem() ?? 0 }} - {{ $warga->lastItem() ?? 0 }}
                    dari {{ $warga->total() }} data
                </p>

                <div>
                    {{ $warga->withQueryString()->links() }}
                </div>
            </div>
        </div>

        {{-- TABEL RIWAYAT DISTRIBUSI --}}
        <div class="bg-white rounded-xl shadow p-6">
            <div class="mb-6">
                <h2 class="text-xl font-bold">Riwayat Distribusi Bantuan</h2>
                <p class="text-gray-500 text-sm">
                    Menampilkan riwayat distribusi bantuan yang sedang diproses maupun sudah selesai.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[1300px]">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-center">No</th>
                            <th class="text-center">No. KK</th>
                            <th class="text-center">Nama Kepala Keluarga</th>
                            <th class="text-center">Desa</th>
                            <th class="text-center">Paket Bantuan</th>
                            <th class="text-center">Jumlah Paket</th>
                            <th class="text-center">Tanggal Distribusi</th>
                            <th class="text-center">Petugas</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($riwayatDistribusi as $item)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-3 text-center">
                                    {{ ($riwayatDistribusi->currentPage() - 1) * $riwayatDistribusi->perPage() + $loop->iteration }}
                                </td>
                                <td class="p-3 pl-4">{{ $item->wargaTerdampak->no_kk ?? '-' }}</td>
                                <td class="p-3 pl-4">{{ $item->wargaTerdampak->nama_kepala_keluarga ?? '-' }}</td>
                                <td class="p-3 pl-4">{{ $item->wargaTerdampak->desa->nama_desa ?? '-' }}</td>
                                <td class="p-3 pl-4">{{ $item->paketBantuan->nama_paket ?? '-' }}</td>
                                <td class="p-3 text-center">{{ $item->jumlah_paket }}</td>
                                <td class="p-3 text-center">
                                    {{ $item->tanggal_distribusi ? \Carbon\Carbon::parse($item->tanggal_distribusi)->format('d-m-Y') : '-' }}
                                </td>
                                <td class="p-3 pl-4">{{ $item->pegawai->nama_pegawai ?? '-' }}</td>

                                <td class="p-3 text-center">
                                    @if ($item->status_distribusi == 'Proses Penyaluran')
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold whitespace-nowrap">
                                            Proses Penyaluran
                                        </span>
                                    @elseif ($item->status_distribusi == 'Sudah disalurkan')
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold whitespace-nowrap">
                                            Sudah disalurkan
                                        </span>
                                    @else
                                        <span class="inline-flex px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs">
                                            {{ $item->status_distribusi }}
                                        </span>
                                    @endif
                                </td>

                                <td class="p-3 text-center">
                                    <div class="flex justify-center items-center gap-2">

                                        {{-- DETAIL --}}
                                        <a href="{{ route('admin.management_distribusi.distribusi_paket.show', $item->id) }}"
                                            class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-xs">
                                            <x-heroicon-o-eye class="w-4 h-4" />
                                            Detail
                                        </a>

                                        {{-- SELESAI --}}
                                        @if ($item->status_distribusi == 'Proses Penyaluran')
                                            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('petugas'))
                                                <form
                                                    action="{{ route('admin.management_distribusi.distribusi_paket.selesai', $item->id) }}"
                                                    method="POST">

                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-md text-xs">

                                                        <x-heroicon-o-check class="w-4 h-4" />
                                                        Selesai

                                                    </button>

                                                </form>
                                            @endif
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center p-4 text-gray-500">
                                    Belum ada data distribusi paket.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Riwayat --}}
            <div class="flex justify-between items-center mt-6 text-sm">
                <p class="text-gray-500">
                    Menampilkan {{ $riwayatDistribusi->firstItem() ?? 0 }} - {{ $riwayatDistribusi->lastItem() ?? 0 }}
                    dari {{ $riwayatDistribusi->total() }} data
                </p>

                <div>
                    {{ $riwayatDistribusi->withQueryString()->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
