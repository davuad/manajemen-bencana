
@extends('layouts.app')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">

        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Detail Foto Pengaduan
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Detail lampiran foto pengaduan bencana masyarakat
            </p>
        </div>

        <a href="/pengaduan"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-medium transition">

            Kembali

        </a>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- FOTO --}}
        <div class="lg:col-span-2">

            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 h-full">

                <div class="overflow-hidden rounded-2xl border bg-white">

                    <img src="{{ asset('foto/'.$foto->file_foto) }}"
                         alt="Foto Pengaduan"
                         class="w-full object-cover">

                </div>

            </div>

        </div>

        {{-- DETAIL --}}
        <div>

            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 h-full">

                <h4 class="text-lg font-semibold text-gray-800 mb-5 border-b pb-3">

                    Informasi Foto

                </h4>

                {{-- ID FOTO --}}
                <div class="mb-5">

                    <p class="text-xs uppercase text-gray-400 font-semibold mb-1">

                        ID Foto

                    </p>

                    <h5 class="text-lg font-bold text-indigo-700">

                        #{{ $foto->id }}

                    </h5>

                </div>

                {{-- PENGADUAN --}}
                <div class="mb-5">

                    <p class="text-xs uppercase text-gray-400 font-semibold mb-2">

                        Pengaduan Terkait

                    </p>

                    <a href="/pengaduan/{{ $foto->pengaduan->id }}"
                       class="inline-block bg-indigo-100 text-indigo-700 px-4 py-2 rounded-xl text-sm font-medium hover:bg-indigo-200 transition">

                        #{{ $foto->pengaduan->id }}
                        -
                        {{ $foto->pengaduan->desa ?? '-' }}

                    </a>

                </div>

                {{-- FILE --}}
                <div class="mb-5">

                    <p class="text-xs uppercase text-gray-400 font-semibold mb-2">

                        Nama File

                    </p>

                    <div class="bg-white border rounded-xl p-3 text-sm text-gray-700 break-all">

                        {{ $foto->file_foto }}

                    </div>

                </div>

                {{-- KETERANGAN --}}
                <div class="mb-6">

                    <p class="text-xs uppercase text-gray-400 font-semibold mb-2">

                        Keterangan Foto

                    </p>

                    <div class="bg-white border rounded-xl p-4 text-sm text-gray-700 leading-relaxed">

                        {{ $foto->keterangan ?? 'Tidak ada keterangan foto.' }}

                    </div>

                </div>

                {{-- BUTTON --}}
                <div class="flex flex-col gap-3">

                    <a href="{{ asset('foto/'.$foto->file_foto) }}"
                       download="{{ $foto->file_foto }}"
                       class="bg-indigo-600 hover:bg-indigo-700 text-white text-center px-4 py-2.5 rounded-xl text-sm font-medium transition">
                        Download Foto
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

