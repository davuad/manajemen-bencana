@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">

        <div>
            <h2 class="text-xl font-bold">
                PENYELESAIAN PENGADUAN
            </h2>

            <p class="text-gray-500 text-sm">
                Monitoring dan penyelesaian pengaduan bencana
            </p>
        </div>

        <a href="{{ route('ketua_tim.pengaduan.index') }}"
           class="bg-gray-500 text-white px-4 py-2 rounded-lg">

            Kembali

        </a>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- DATA PENGADUAN --}}
        <div class="lg:col-span-2">

            <div class="border rounded-xl p-5">

                <h3 class="font-bold text-lg mb-4">
                    Detail Pengaduan
                </h3>

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="text-gray-500 text-sm">
                            Pelapor
                        </label>

                        <p class="font-semibold">
                            {{ $data->user->nama ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <label class="text-gray-500 text-sm">
                            Kategori
                        </label>

                        <p class="font-semibold">
                            {{ $data->kategori->nama_kategori ?? '-' }}
                        </p>
                    </div>

                </div>

                <div class="mt-4">

                    <label class="text-gray-500 text-sm">
                        Desa
                    </label>

                    <p class="font-semibold">
                        {{ $data->desa }}
                    </p>

                </div>

                <div class="mt-4">

                    <label class="text-gray-500 text-sm">
                        Deskripsi
                    </label>

                    <p class="mt-1">
                        {{ $data->deskripsi }}
                    </p>

                </div>

                <div class="mt-4">

                    <label class="text-gray-500 text-sm">
                        Status Saat Ini
                    </label>

                    <div class="mt-2">

                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">

                            {{ $data->status_pengaduan }}

                        </span>

                    </div>

                </div>

            </div>

            {{-- FOTO --}}
            <div class="border rounded-xl p-5 mt-6">

                <h3 class="font-bold text-lg mb-4">
                    Lampiran Foto
                </h3>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

                    @forelse($data->foto as $foto)

                        <a href="{{ asset('foto/'.$foto->file_foto) }}"
                           target="_blank">

                            <img src="{{ asset('foto/'.$foto->file_foto) }}"
                                 class="rounded-lg border h-40 w-full object-cover">

                        </a>

                    @empty

                        <p class="text-gray-500">
                            Tidak ada foto
                        </p>

                    @endforelse

                </div>

            </div>

        </div>

        {{-- SIDEBAR --}}
        <div>

            {{-- KEBUTUHAN --}}
            <div class="border rounded-xl p-5">

                <h3 class="font-bold text-lg mb-4">
                    Kebutuhan
                </h3>

                @if($data->kebutuhan)

                    <ul class="space-y-2 text-sm">

                        <li>
                            Dapur Umum :
                            <b>{{ $data->kebutuhan->dapur_umum }}</b>
                        </li>

                        <li>
                            Psikososial :
                            <b>{{ $data->kebutuhan->psikososial }}</b>
                        </li>

                        <li>
                            Logistik Rentan :
                            <b>{{ $data->kebutuhan->logistik_rentan }}</b>
                        </li>

                        <li>
                            Logistik Makanan :
                            <b>{{ $data->kebutuhan->logistik_makanan }}</b>
                        </li>

                        <li>
                            Logistik Penampungan :
                            <b>{{ $data->kebutuhan->logistik_penampungan }}</b>
                        </li>

                    </ul>

                @else

                    <p class="text-gray-500">
                        Tidak ada data kebutuhan
                    </p>

                @endif

            </div>

            {{-- FORM SELESAI --}}
            <div class="border rounded-xl p-5 mt-6">

                <h3 class="font-bold text-lg mb-4">
                    Penyelesaian
                </h3>

                <form action="{{ route('ketua_tim.pengaduan.simpan',$data->id) }}"
                      method="POST">

                    @csrf
                    @method('PUT')

                    <div>

                        <label class="block mb-2 text-sm">
                            Tanggal Selesai
                        </label>

                        <input type="date"
                               name="tanggal_selesai"
                               value="{{ date('Y-m-d') }}"
                               class="w-full border rounded-lg px-3 py-2"
                               required>

                    </div>

                    <button type="submit"
                            class="w-full mt-4 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg">

                        Selesaikan Pengaduan

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection