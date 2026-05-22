@extends('layouts.app')

@section('title', 'Detail Data Warga Terdampak')

@section('content')
    <div class="space-y-6">
        {{-- Breadcrumb --}}
        <div class="text-sm text-gray-500">
            Data Warga Terdampak <span class="mx-1">&gt;</span> Detail Data Warga
        </div>

        {{-- Card --}}
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            {{-- Header --}}
            <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 uppercase tracking-wide">
                        Detail Data Warga Terdampak
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Informasi lengkap keluarga terdampak untuk proses penyaluran bantuan.
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <a
                        href="{{ route('warga.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                    >
                        Kembali
                    </a>

                    <a
                        href="{{ route('warga.edit', $warga->id) }}"
                        class="inline-flex items-center justify-center rounded-xl bg-orange-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-orange-600"
                    >
                        Edit Data
                    </a>
                </div>
            </div>

            {{-- Body --}}
            <div class="space-y-6 px-6 py-6">
                {{-- Top Grid --}}
                <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
                    {{-- Profile --}}
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 lg:col-span-2">
                        <div class="mb-5 flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-sm font-bold text-indigo-700">
                                {{ str_pad($warga->id, 2, '0', STR_PAD_LEFT) }}
                            </div>

                            <div>
                                <h3 class="text-xl font-bold text-gray-900">
                                    {{ $warga->nama_kepala_keluarga }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{-- Diubah ke relasi bencana --}}
                                    Kepala Keluarga · {{ $warga->desa?->nama_desa ?? '-' }} · {{ $warga->bencana?->nama_bencana ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                <p class="text-xs font-medium text-gray-400">ID Warga</p>
                                <p class="mt-2 text-base font-semibold text-gray-900">{{ $warga->id }}</p>
                            </div>

                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                <p class="text-xs font-medium text-gray-400">Jumlah Anggota</p>
                                <p class="mt-2 text-base font-semibold text-gray-900">{{ $warga->jumlah_anggota }} Orang</p>
                            </div>

                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                <p class="text-xs font-medium text-gray-400">Tanggal Pendataan</p>
                                <p class="mt-2 text-base font-semibold text-gray-900">
                                    {{ $warga->tanggal_pendataan ? \Carbon\Carbon::parse($warga->tanggal_pendataan)->translatedFormat('d M Y') : '-' }}
                                </p>
                            </div>

                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                <p class="text-xs font-medium text-gray-400">Tanggal Penyaluran</p>
                                <p class="mt-2 text-base font-semibold text-gray-900">
                                    {{ $warga->tanggal_penyaluran ? \Carbon\Carbon::parse($warga->tanggal_penyaluran)->translatedFormat('d M Y') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="space-y-4 rounded-2xl border border-gray-200 bg-white p-5">
                        <div>
                            <p class="text-xs font-medium text-gray-400">Jenis Bantuan</p>
                            <div class="mt-2">
                                <span class="inline-flex rounded-full bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700">
                                    {{ $warga->jenis_bantuan }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-400">Status Penyaluran</p>
                            <div class="mt-2">
                                @if ($warga->status_penyaluran == 'Belum diproses')
                                    <span class="inline-flex rounded-full bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700">
                                        Belum Diproses
                                    </span>
                                @elseif ($warga->status_penyaluran == 'Proses Penyaluran')
                                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700">
                                        Proses Penyaluran
                                    </span>
                                @elseif ($warga->status_penyaluran == 'Sudah disalurkan')
                                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1.5 text-xs font-semibold text-green-700">
                                        Sudah Disalurkan
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700">
                                        {{ $warga->status_penyaluran }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Detail Section --}}
                <div>
                    <h3 class="mb-4 text-lg font-bold text-gray-900">
                        Informasi Lengkap
                    </h3>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="rounded-2xl border border-gray-200 bg-white p-4">
                            <p class="text-xs font-medium text-gray-400">ID Warga</p>
                            <p class="mt-2 text-base font-semibold break-words text-gray-900">{{ $warga->id }}</p>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-4">
                            <p class="text-xs font-medium text-gray-400">No. KK</p>
                            <p class="mt-2 text-base font-semibold break-words text-gray-900">{{ $warga->no_kk }}</p>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-4">
                            <p class="text-xs font-medium text-gray-400">NIK Kepala Keluarga</p>
                            <p class="mt-2 text-base font-semibold break-words text-gray-900">{{ $warga->nik_kepala_keluarga }}</p>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-4">
                            <p class="text-xs font-medium text-gray-400">Nama Kepala Keluarga</p>
                            <p class="mt-2 text-base font-semibold break-words text-gray-900">{{ $warga->nama_kepala_keluarga }}</p>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-4 md:col-span-2">
                            <p class="text-xs font-medium text-gray-400">Alamat</p>
                            <p class="mt-2 text-base font-semibold break-words text-gray-900">{{ $warga->alamat }}</p>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-4">
                            <p class="text-xs font-medium text-gray-400">Desa</p>
                            <p class="mt-2 text-base font-semibold break-words text-gray-900">{{ $warga->desa?->nama_desa ?? '-' }}</p>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-4">
                            <p class="text-xs font-medium text-gray-400">Bencana</p>
                            {{-- Diubah ke relasi bencana --}}
                            <p class="mt-2 text-base font-semibold break-words text-gray-900">{{ $warga->bencana?->nama_bencana ?? '-' }}</p>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-4">
                            <p class="text-xs font-medium text-gray-400">Jumlah Anggota</p>
                            <p class="mt-2 text-base font-semibold break-words text-gray-900">{{ $warga->jumlah_anggota }}</p>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-4">
                            <p class="text-xs font-medium text-gray-400">Tanggal Pendataan</p>
                            <p class="mt-2 text-base font-semibold break-words text-gray-900">
                                {{ $warga->tanggal_pendataan ? \Carbon\Carbon::parse($warga->tanggal_pendataan)->format('Y-m-d') : '-' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-4">
                            <p class="text-xs font-medium text-gray-400">Jenis Bantuan</p>
                            <p class="mt-2 text-base font-semibold break-words text-gray-900">{{ $warga->jenis_bantuan }}</p>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-4">
                            <p class="text-xs font-medium text-gray-400">Status Penyaluran</p>
                            <p class="mt-2 text-base font-semibold break-words text-gray-900">{{ $warga->status_penyaluran }}</p>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-4 md:col-span-2">
                            <p class="text-xs font-medium text-gray-400">Tanggal Penyaluran</p>
                            <p class="mt-2 text-base font-semibold break-words text-gray-900">
                                {{ $warga->tanggal_penyaluran ? \Carbon\Carbon::parse($warga->tanggal_penyaluran)->format('Y-m-d') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection