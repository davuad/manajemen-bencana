@extends('layouts.app')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

{{-- Header --}}
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">

    <div>

        <h2 class="text-2xl font-bold text-gray-800">
            Detail Pengaduan
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Informasi lengkap pengaduan bencana yang telah Anda laporkan.
        </p>

    </div>

    <a href="{{ route('user.pengaduan.index') }}"
        class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">

        Kembali

    </a>

</div>

{{-- Status --}}
<div class="border rounded-xl p-6 mb-6">

    <h3 class="text-lg font-semibold text-gray-700 mb-4">
        Status Pengaduan
    </h3>

    @if($data->status_pengaduan == 'BELUM_DITANGANI')

        <span class="inline-flex px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 text-sm font-semibold">
            Belum Ditangani
        </span>

    @elseif($data->status_pengaduan == 'DITANGANI')

        <span class="inline-flex px-4 py-2 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">
            Sedang Ditangani
        </span>

    @elseif($data->status_pengaduan == 'SELESAI')

        <span class="inline-flex px-4 py-2 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
            Selesai
        </span>

    @endif

</div>

{{-- Data Pengaduan --}}
<div class="border rounded-xl p-6 mb-6">

    <h3 class="text-lg font-semibold text-indigo-700 mb-5">
        Data Pengaduan
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            <p class="text-sm text-gray-500">Nama Pelapor</p>
            <p class="font-semibold text-gray-800">
                {{ $data->user->nama ?? '-' }}
            </p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Kategori Bencana</p>
            <p class="font-semibold text-gray-800">
                {{ $data->kategori->nama_kategori ?? '-' }}
            </p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Desa</p>
            <p class="font-semibold text-gray-800">
                {{ $data->desa }}
            </p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Tanggal Pengaduan</p>
            <p class="font-semibold text-gray-800">
                {{ $data->created_at->format('d-m-Y H:i') }}
            </p>
        </div>

        @if($data->tanggal_selesai)

            <div>
                <p class="text-sm text-gray-500">Tanggal Selesai</p>
                <p class="font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($data->tanggal_selesai)->format('d-m-Y') }}
                </p>
            </div>

        @endif

        <div class="md:col-span-2">

            <p class="text-sm text-gray-500 mb-2">
                Deskripsi Kejadian
            </p>

            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">

                {{ $data->deskripsi }}

            </div>

        </div>

    </div>

</div>

{{-- Dokumentasi --}}
<div class="border rounded-xl p-6">

    <h3 class="text-lg font-semibold text-sky-700 mb-5">
        Dokumentasi Foto
    </h3>

    @if($data->foto->count())

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($data->foto as $foto)

                <div class="rounded-xl overflow-hidden border border-gray-200 shadow-sm">

                    <img
                        src="{{ asset('foto/' . $foto->file_foto) }}"
                        class="w-full h-60 object-cover">

                    <div class="p-4">

                        <p class="text-sm text-gray-600">

                            {{ $foto->keterangan ?: '-' }}

                        </p>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="rounded-xl bg-yellow-50 border border-yellow-300 p-4 text-yellow-700">

            Belum ada dokumentasi foto.

        </div>

    @endif

</div>

</div>

@endsection
