@extends('layouts.app')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">

        <div>

            <h2 class="text-2xl font-bold text-gray-800">
                Penyelesaian Pengaduan
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Monitoring dan penyelesaian pengaduan bencana.
            </p>

        </div>

        <a href="{{ route('ketua_tim.pengaduan.index') }}"
            class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">

            Kembali

        </a>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ========================= --}}
        {{-- DETAIL PENGADUAN --}}
        {{-- ========================= --}}

        <div class="lg:col-span-2 space-y-6">

            <div class="border rounded-2xl p-6">

                <h3 class="text-lg font-semibold text-indigo-700 mb-6">

                    Data Pengaduan

                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>

                        <label class="text-sm text-gray-500">
                            Nama Pelapor
                        </label>

                        <p class="font-semibold text-gray-800 mt-1">

                            {{ $data->user->nama ?? '-' }}

                        </p>

                    </div>

                    <div>

                        <label class="text-sm text-gray-500">
                            Kategori Bencana
                        </label>

                        <p class="font-semibold text-gray-800 mt-1">

                            {{ $data->kategori->nama_kategori ?? '-' }}

                        </p>

                    </div>

                    <div>

                        <label class="text-sm text-gray-500">
                            Desa
                        </label>

                        <p class="font-semibold text-gray-800 mt-1">

                            {{ $data->desa }}

                        </p>

                    </div>

                    <div>

                        <label class="text-sm text-gray-500">
                            Tanggal Pengaduan
                        </label>

                        <p class="font-semibold text-gray-800 mt-1">

                            {{ $data->created_at->format('d-m-Y H:i') }}

                        </p>

                    </div>

                    <div class="md:col-span-2">

                        <label class="text-sm text-gray-500">

                            Deskripsi Kejadian

                        </label>

                        <div class="mt-2 rounded-xl border border-gray-200 bg-gray-50 p-4 leading-relaxed">

                            {{ $data->deskripsi }}

                        </div>

                    </div>

                </div>

            </div>

            {{-- STATUS --}}

            <div class="border rounded-2xl p-6">

                <h3 class="text-lg font-semibold text-indigo-700 mb-5">

                    Status Saat Ini

                </h3>

                @if($data->status_pengaduan == 'BELUM_DITANGANI')

                    <span class="inline-flex px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 font-semibold">

                        Belum Ditangani

                    </span>

                @elseif($data->status_pengaduan == 'DITANGANI')

                    <span class="inline-flex px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-semibold">

                        Sedang Ditangani

                    </span>

                @elseif($data->status_pengaduan == 'SELESAI')

                    <span class="inline-flex px-4 py-2 rounded-full bg-green-100 text-green-700 font-semibold">

                        Selesai

                    </span>

                @endif

            </div>

            {{-- FOTO --}}

            <div class="border rounded-2xl p-6">

                <h3 class="text-lg font-semibold text-indigo-700 mb-6">

                    Dokumentasi Pengaduan

                </h3>

                @if($data->foto->count())

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                        @foreach($data->foto as $foto)

                            <div class="border rounded-xl overflow-hidden shadow-sm">

                                <a href="{{ asset('foto/'.$foto->file_foto) }}"
                                   target="_blank">

                                    <img
                                        src="{{ asset('foto/'.$foto->file_foto) }}"
                                        class="w-full h-52 object-cover">

                                </a>

                                <div class="p-4">

                                    <h5 class="font-semibold text-gray-700 mb-2">

                                        Keterangan Foto

                                    </h5>

                                    <p class="text-sm text-gray-500">

                                        {{ $foto->keterangan ?: '-' }}

                                    </p>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="rounded-xl border border-yellow-300 bg-yellow-50 p-4 text-yellow-700">

                        Belum ada dokumentasi foto.

                    </div>

                @endif

            </div>

        </div>

        {{-- ========================= --}}
        {{-- SIDEBAR --}}
        {{-- ========================= --}}

        <div class="space-y-6">
                        {{-- ========================= --}}
            {{-- KEBUTUHAN BANTUAN --}}
            {{-- ========================= --}}
            <div class="border rounded-2xl p-6">

                <h3 class="text-lg font-semibold text-indigo-700 mb-6">
                    Hasil Verifikasi Kabid
                </h3>

                @if($data->kebutuhan)

                    <div class="space-y-4">

                        <div class="flex justify-between items-center border-b pb-2">
                            <span>Dapur Umum</span>

                            @if($data->kebutuhan->dapur_umum == 'Butuh')
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                                    Butuh
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-sm">
                                    Tidak
                                </span>
                            @endif
                        </div>

                        <div class="flex justify-between items-center border-b pb-2">
                            <span>Psikososial</span>

                            @if($data->kebutuhan->psikososial == 'Butuh')
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                                    Butuh
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-sm">
                                    Tidak
                                </span>
                            @endif
                        </div>

                        <div class="flex justify-between items-center border-b pb-2">
                            <span>Logistik Rentan</span>

                            @if($data->kebutuhan->logistik_rentan == 'Butuh')
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                                    Butuh
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-sm">
                                    Tidak
                                </span>
                            @endif
                        </div>

                        <div class="flex justify-between items-center border-b pb-2">
                            <span>Logistik Makanan</span>

                            @if($data->kebutuhan->logistik_makanan == 'Butuh')
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                                    Butuh
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-sm">
                                    Tidak
                                </span>
                            @endif
                        </div>

                        <div class="flex justify-between items-center">
                            <span>Logistik Penampungan</span>

                            @if($data->kebutuhan->logistik_penampungan == 'Butuh')
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                                    Butuh
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-sm">
                                    Tidak
                                </span>
                            @endif
                        </div>

                    </div>

                    <div class="mt-6">

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Keterangan Kebutuhan
                        </label>

                        <div class="rounded-xl bg-gray-50 border border-gray-200 p-4 text-sm leading-relaxed">

                            {{ $data->kebutuhan->keterangan ?: '-' }}

                        </div>

                    </div>

                @else

                    <div class="rounded-xl border border-yellow-300 bg-yellow-50 p-4 text-yellow-700">

                        Belum ada hasil verifikasi dari Kabid.

                    </div>

                @endif

            </div>

            {{-- ========================= --}}
            {{-- PENYELESAIAN --}}
            {{-- ========================= --}}
            <div class="border rounded-2xl p-6">

                <h3 class="text-lg font-semibold text-indigo-700 mb-6">

                    Penyelesaian Pengaduan

                </h3>

                <form action="{{ route('ketua_tim.pengaduan.simpan',$data->id) }}"
                      method="POST">

                    @csrf
                    @method('PUT')

                    <div class="mb-5">

                        <label class="block text-sm font-medium text-gray-700 mb-2">

                            Tanggal Selesai

                        </label>

                        <input
                            type="date"
                            name="tanggal_selesai"
                            value="{{ $data->tanggal_selesai ?? date('Y-m-d') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>

                    </div>

                    <div class="mb-6 rounded-xl bg-blue-50 border border-blue-200 p-4">

                        <h4 class="font-semibold text-blue-700 mb-2">

                            Informasi

                        </h4>

                        <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">

                            <li>Pastikan seluruh bantuan telah disalurkan.</li>
                            <li>Pastikan proses penanganan telah selesai.</li>
                            <li>Setelah disimpan, status pengaduan akan berubah menjadi <strong>SELESAI</strong>.</li>

                        </ul>

                    </div>

                    <button
                        type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-semibold transition">

                        Selesaikan Pengaduan

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection