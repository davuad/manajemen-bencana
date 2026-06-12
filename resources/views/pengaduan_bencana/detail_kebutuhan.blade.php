
@extends('layouts.app')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">

        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Detail Kebutuhan Pengaduan
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Informasi kebutuhan darurat dari laporan pengaduan bencana
            </p>
        </div>

        <a href="/pengaduan"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-medium transition">

            Kembali

        </a>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- KEBUTUHAN --}}
        <div class="lg:col-span-2">

            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 h-full">

                <h4 class="text-lg font-semibold text-gray-800 mb-6 border-b pb-3">

                    Status Kebutuhan Darurat

                </h4>

                @php
                    function isButuh($val) {
                        return $val == 'Butuh';
                    }

                    $items = [
                        [
                            'label' => 'Dapur Umum',
                            'value' => $data->dapur_umum,
                            'icon' => '🍳'
                        ],
                        [
                            'label' => 'Psikososial',
                            'value' => $data->psikososial,
                            'icon' => '👥'
                        ],
                        [
                            'label' => 'Logistik Rentan',
                            'value' => $data->logistik_rentan,
                            'icon' => '🧑‍🦽'
                        ],
                        [
                            'label' => 'Logistik Makanan',
                            'value' => $data->logistik_makanan,
                            'icon' => '📦'
                        ],
                        [
                            'label' => 'Logistik Penampungan',
                            'value' => $data->logistik_penampungan,
                            'icon' => '🏠'
                        ],
                    ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    @foreach($items as $item)

                    <div class="bg-white border rounded-2xl p-5">

                        <div class="flex items-center justify-between mb-4">

                            <div class="flex items-center gap-3">

                                <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-indigo-100 text-xl">

                                    {{ $item['icon'] }}

                                </div>

                                <div>

                                    <h5 class="font-semibold text-gray-800">

                                        {{ $item['label'] }}

                                    </h5>

                                </div>

                            </div>

                        </div>

                        @if(isButuh($item['value']))

                            <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-medium">

                                Butuh

                            </span>

                        @else

                            <span class="bg-gray-200 text-gray-700 text-xs px-3 py-1 rounded-full font-medium">

                                Tidak

                            </span>

                        @endif

                    </div>

                    @endforeach

                </div>

            </div>

        </div>

        {{-- INFORMASI --}}
        <div>

            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 h-full">

                <h4 class="text-lg font-semibold text-gray-800 mb-5 border-b pb-3">

                    Informasi Data

                </h4>

                {{-- ID --}}
                <div class="mb-5">

                    <p class="text-xs uppercase text-gray-400 font-semibold mb-1">

                        ID Kebutuhan

                    </p>

                    <h5 class="text-lg font-bold text-indigo-700">

                        #{{ $data->id }}

                    </h5>

                </div>

                {{-- PENGADUAN --}}
                <div class="mb-5">

                    <p class="text-xs uppercase text-gray-400 font-semibold mb-2">

                        Pengaduan Terkait

                    </p>

                    <a href="/pengaduan/{{ $data->pengaduan->id }}"
                       class="inline-block bg-indigo-100 text-indigo-700 px-4 py-2 rounded-xl text-sm font-medium hover:bg-indigo-200 transition">

                        #{{ $data->pengaduan->id }}
                        -
                        {{ $data->pengaduan->desa ?? '-' }}

                    </a>

                </div>

                {{-- STATUS --}}
                <div class="mb-5">

                    <p class="text-xs uppercase text-gray-400 font-semibold mb-2">

                        Status Pengaduan

                    </p>

                    @if($data->pengaduan->status_pengaduan == 'BELUM_DITANGANI')

                        <span class="bg-gray-200 text-gray-700 text-xs px-3 py-1 rounded-full font-medium">

                            Belum Ditangani

                        </span>

                    @elseif($data->pengaduan->status_pengaduan == 'DITANGANI')

                        <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full font-medium">

                            Ditangani

                        </span>

                    @elseif($data->pengaduan->status_pengaduan == 'SELESAI')

                        <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-medium">

                            Selesai

                        </span>

                    @else

                        <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full font-medium">

                            Ditolak

                        </span>

                    @endif

                </div>

                {{-- KETERANGAN --}}
                <div>

                    <p class="text-xs uppercase text-gray-400 font-semibold mb-2">

                        Catatan Tambahan

                    </p>

                    <div class="bg-white border rounded-2xl p-4 text-sm text-gray-700 leading-relaxed">

                        {{ $data->keterangan ?? 'Tidak ada catatan tambahan.' }}

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

