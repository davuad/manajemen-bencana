@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">

        <div>
            <h2 class="text-xl font-bold">
                VERIFIKASI PENGADUAN
            </h2>

            <p class="text-gray-500 text-sm">
                Verifikasi laporan pengaduan bencana
            </p>
        </div>

        <a href="{{ route('kabid.pengaduan.index') }}"
           class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg">

            Kembali

        </a>

    </div>

    <form action="{{ route('kabid.pengaduan.simpan', $data->id) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- DETAIL PENGADUAN --}}
            <div class="lg:col-span-2">

                <div class="border rounded-xl p-5">

                    <h4 class="font-semibold text-lg mb-4">
                        Data Pengaduan
                    </h4>

                    <div class="space-y-4">

                        <div>
                            <label class="text-sm text-gray-500">
                                Pelapor
                            </label>

                            <div class="font-medium">
                                {{ $data->user->nama ?? '-' }}
                            </div>
                        </div>

                        <div>
                            <label class="text-sm text-gray-500">
                                Kategori
                            </label>

                            <div class="font-medium">
                                {{ $data->kategori->nama_kategori ?? '-' }}
                            </div>
                        </div>

                        <div>
                            <label class="text-sm text-gray-500">
                                Desa
                            </label>

                            <div class="font-medium">
                                {{ $data->desa }}
                            </div>
                        </div>

                        <div>
                            <label class="text-sm text-gray-500">
                                Deskripsi
                            </label>

                            <div class="border rounded-lg p-3 bg-gray-50">
                                {{ $data->deskripsi }}
                            </div>
                        </div>

                    </div>

                </div>

                {{-- FOTO --}}
                <div class="border rounded-xl p-5 mt-6">

                    <h4 class="font-semibold text-lg mb-4">
                        Lampiran Foto
                    </h4>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

                        @forelse($data->foto as $foto)

                            <img
                                src="{{ asset('foto/'.$foto->file_foto) }}"
                                class="rounded-lg border h-40 w-full object-cover">

                        @empty

                            <p class="text-gray-500">
                                Tidak ada foto
                            </p>

                        @endforelse

                    </div>

                </div>

            </div>

            {{-- VERIFIKASI --}}
            <div>

                <div class="border rounded-xl p-5">

                    <h4 class="font-semibold text-lg mb-4">
                        Verifikasi Kabid
                    </h4>

                    {{-- KEBUTUHAN --}}
                    <div class="space-y-3 mb-6">

                        <label class="font-medium">
                            Kebutuhan
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="checkbox"
                                   name="dapur_umum"
                                   value="Butuh"
                                   {{ optional($data->kebutuhan)->dapur_umum == 'Butuh' ? 'checked' : '' }}>
                            Dapur Umum
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="checkbox"
                                   name="psikososial"
                                   value="Butuh"
                                   {{ optional($data->kebutuhan)->psikososial == 'Butuh' ? 'checked' : '' }}>
                            Psikososial
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="checkbox"
                                   name="logistik_rentan"
                                   value="Butuh"
                                   {{ optional($data->kebutuhan)->logistik_rentan == 'Butuh' ? 'checked' : '' }}>
                            Logistik Rentan
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="checkbox"
                                   name="logistik_makanan"
                                   value="Butuh"
                                   {{ optional($data->kebutuhan)->logistik_makanan == 'Butuh' ? 'checked' : '' }}>
                            Logistik Makanan
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="checkbox"
                                   name="logistik_penampungan"
                                   value="Butuh"
                                   {{ optional($data->kebutuhan)->logistik_penampungan == 'Butuh' ? 'checked' : '' }}>
                            Logistik Penampungan
                        </label>

                    </div>

                    {{-- STATUS --}}
                    <div class="mb-5">

                        <label class="block mb-2 font-medium">
                            Status Pengaduan
                        </label>

                        <select name="status_pengaduan"
                                class="w-full border rounded-lg px-3 py-2">

                            <option value="BELUM_DITANGANI"
                                {{ $data->status_pengaduan == 'BELUM_DITANGANI' ? 'selected' : '' }}>
                                Belum Ditangani
                            </option>

                            <option value="DITANGANI"
                                {{ $data->status_pengaduan == 'DITANGANI' ? 'selected' : '' }}>
                                Ditangani
                            </option>

                            <option value="TIDAK_DIREKOMENDASIKAN"
                                {{ $data->status_pengaduan == 'TIDAK_DIREKOMENDASIKAN' ? 'selected' : '' }}>
                                Tidak Direkomendasikan
                            </option>

                        </select>

                    </div>

                    {{-- KETERANGAN --}}
                    <div class="mb-6">

                        <label class="block mb-2 font-medium">
                            Keterangan Verifikasi
                        </label>

                        <textarea
                            name="keterangan_verifikasi"
                            rows="5"
                            class="w-full border rounded-lg px-3 py-2">{{ $data->keterangan_verifikasi }}</textarea>

                    </div>

                    <button
                        type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg">

                        Simpan Verifikasi

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection