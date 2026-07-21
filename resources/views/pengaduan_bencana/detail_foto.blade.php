@extends('layouts.app')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">

        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Detail Lampiran Pengaduan
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Detail lampiran foto maupun dokumen PDF pengaduan bencana.
            </p>
        </div>

        <a href="{{ route('admin.pengaduan_bencana.index') }}"
            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-medium transition">

            Kembali

        </a>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LAMPIRAN --}}
        <div class="lg:col-span-2">

            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5">

                @if($pengaduan->foto->count())

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        @foreach($pengaduan->foto as $file)

                            @php
                                $ext = strtolower(pathinfo($file->file_foto, PATHINFO_EXTENSION));
                            @endphp

                            <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">

                                {{-- FOTO --}}
                                @if(in_array($ext, ['jpg','jpeg','png','webp']))

                                    <img src="{{ asset('lampiran_pengaduan/'.$file->file_foto) }}"
                                        class="w-full h-64 object-cover">

                                {{-- PDF --}}
                                @elseif($ext == 'pdf')

                                    <div class="h-64 flex flex-col items-center justify-center bg-gray-100">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-20 h-20 text-red-600"
                                            fill="currentColor"
                                            viewBox="0 0 24 24">

                                            <path
                                                d="M6 2h8l4 4v16H6V2zm8 1.5V7h3.5L14 3.5z" />

                                        </svg>

                                        <p class="mt-3 font-semibold text-gray-700">
                                            Dokumen PDF
                                        </p>

                                    </div>

                                @else

                                    <div class="h-64 flex items-center justify-center">

                                        File tidak didukung

                                    </div>

                                @endif

                                {{-- FOOTER --}}
                                <div class="p-4">

                                    <p class="text-sm text-gray-600 break-all">

                                        {{ $file->file_foto }}

                                    </p>

                                    @if($file->keterangan)

                                        <p class="text-xs text-gray-500 mt-2">

                                            {{ $file->keterangan }}

                                        </p>

                                    @endif

                                    <div class="flex gap-2 mt-4">

                                        <a href="{{ asset('lampiran_pengaduan/'.$file->file_foto) }}"
                                            target="_blank"
                                            class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 rounded-lg text-sm">

                                            Lihat

                                        </a>

                                        <a href="{{ asset('lampiran_pengaduan/'.$file->file_foto) }}"
                                            download
                                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 rounded-lg text-sm">

                                            Download

                                        </a>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="text-center py-20 text-gray-500">

                        Tidak ada lampiran.

                    </div>

                @endif

            </div>

        </div>

        {{-- INFORMASI --}}
        <div>

            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5">

                <h4 class="text-lg font-semibold text-gray-800 mb-5 border-b pb-3">

                    Informasi Pengaduan

                </h4>

                <div class="space-y-5">

                    <div>

                        <p class="text-xs uppercase text-gray-400 font-semibold">

                            ID Pengaduan

                        </p>

                        <div class="bg-white border rounded-xl p-3 mt-1 font-semibold text-indigo-700">

                            #{{ $pengaduan->id }}

                        </div>

                    </div>

                    <div>

                        <p class="text-xs uppercase text-gray-400 font-semibold">

                            Nama Pelapor

                        </p>

                        <div class="bg-white border rounded-xl p-3 mt-1">

                            {{ $pengaduan->user->nama ?? '-' }}

                        </div>

                    </div>

                    <div>

                        <p class="text-xs uppercase text-gray-400 font-semibold">

                            Kategori Bencana

                        </p>

                        <div class="bg-white border rounded-xl p-3 mt-1">

                            {{ $pengaduan->kategori->nama_kategori ?? '-' }}

                        </div>

                    </div>

                    <div>

                        <p class="text-xs uppercase text-gray-400 font-semibold">

                            Desa

                        </p>

                        <div class="bg-white border rounded-xl p-3 mt-1">

                            {{ $pengaduan->desa }}

                        </div>

                    </div>

                    <div>

                        <p class="text-xs uppercase text-gray-400 font-semibold">

                            Status

                        </p>

                        <div class="bg-white border rounded-xl p-3 mt-1">

                            {{ ucfirst($pengaduan->status_pengaduan) }}

                        </div>

                    </div>

                    <div>

                        <p class="text-xs uppercase text-gray-400 font-semibold">

                            Jumlah Lampiran

                        </p>

                        <div class="bg-indigo-100 text-indigo-700 rounded-xl p-3 mt-1 font-semibold">

                            {{ $pengaduan->foto->count() }} Lampiran

                        </div>

                    </div>

                    @if($pengaduan->keterangan)

                        <div>

                            <p class="text-xs uppercase text-gray-400 font-semibold">

                                Keterangan

                            </p>

                            <div class="bg-white border rounded-xl p-3 mt-1 text-sm">

                                {{ $pengaduan->keterangan }}

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection