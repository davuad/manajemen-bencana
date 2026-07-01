@extends('layouts.app')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">

        <div>

            <h2 class="text-2xl font-bold text-gray-800">
                Verifikasi Pengaduan
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Verifikasi laporan pengaduan bencana dari masyarakat.
            </p>

        </div>

        <a href="{{ route('kabid.pengaduan.index') }}"
            class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">

            Kembali

        </a>

    </div>

    @if ($errors->any())

        <div class="mb-6 rounded-xl border border-red-300 bg-red-50 p-4">

            <h5 class="font-semibold text-red-700 mb-3">
                Terjadi Kesalahan
            </h5>

            <ul class="list-disc list-inside text-red-600 text-sm">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form action="{{ route('kabid.pengaduan.simpan',$data->id) }}"
          method="POST">

        @csrf
        @method('PUT')

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

                {{-- ========================= --}}
                {{-- STATUS SAAT INI --}}
                {{-- ========================= --}}

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

                {{-- ========================= --}}
                {{-- DOKUMENTASI FOTO --}}
                {{-- ========================= --}}

                <div class="border rounded-2xl p-6">

                    <h3 class="text-lg font-semibold text-indigo-700 mb-6">

                        Dokumentasi Pengaduan

                    </h3>

                    @if($data->foto->count())

                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                            @foreach($data->foto as $foto)

                                <div class="border rounded-xl overflow-hidden shadow-sm">

                                    <img
                                        src="{{ asset('foto/'.$foto->file_foto) }}"
                                        class="w-full h-52 object-cover">

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
            {{-- VERIFIKASI KABID --}}
            {{-- ========================= --}}
            <div>

                <div class="border rounded-2xl p-6">
                                        <h3 class="text-lg font-semibold text-indigo-700 mb-6">

                        Hasil Verifikasi

                    </h3>

                    {{-- ========================= --}}
                    {{-- KEBUTUHAN BANTUAN --}}
                    {{-- ========================= --}}

                    <div class="mb-6">

                        <label class="block text-sm font-semibold text-gray-700 mb-4">

                            Kebutuhan Bantuan

                        </label>

                        <div class="space-y-3">

                            <label class="flex items-center gap-3">

                                <input
                                    type="checkbox"
                                    name="dapur_umum"
                                    value="Butuh"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    {{ optional($data->kebutuhan)->dapur_umum == 'Butuh' ? 'checked' : '' }}>

                                <span>Dapur Umum</span>

                            </label>

                            <label class="flex items-center gap-3">

                                <input
                                    type="checkbox"
                                    name="psikososial"
                                    value="Butuh"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    {{ optional($data->kebutuhan)->psikososial == 'Butuh' ? 'checked' : '' }}>

                                <span>Psikososial</span>

                            </label>

                            <label class="flex items-center gap-3">

                                <input
                                    type="checkbox"
                                    name="logistik_rentan"
                                    value="Butuh"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    {{ optional($data->kebutuhan)->logistik_rentan == 'Butuh' ? 'checked' : '' }}>

                                <span>Logistik Rentan</span>

                            </label>

                            <label class="flex items-center gap-3">

                                <input
                                    type="checkbox"
                                    name="logistik_makanan"
                                    value="Butuh"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    {{ optional($data->kebutuhan)->logistik_makanan == 'Butuh' ? 'checked' : '' }}>

                                <span>Logistik Makanan</span>

                            </label>

                            <label class="flex items-center gap-3">

                                <input
                                    type="checkbox"
                                    name="logistik_penampungan"
                                    value="Butuh"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    {{ optional($data->kebutuhan)->logistik_penampungan == 'Butuh' ? 'checked' : '' }}>

                                <span>Logistik Penampungan</span>

                            </label>

                        </div>

                    </div>

                    {{-- ========================= --}}
                    {{-- STATUS --}}
                    {{-- ========================= --}}

                    <div class="mb-6">

                        <label class="block text-sm font-semibold text-gray-700 mb-2">

                            Status Pengaduan

                        </label>

                        <select
                            name="status_pengaduan"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

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

                    {{-- ========================= --}}
                    {{-- KETERANGAN KEBUTUHAN --}}
                    {{-- ========================= --}}

                    <div class="mb-6">

                        <label class="block text-sm font-semibold text-gray-700 mb-2">

                            Keterangan Kebutuhan

                        </label>

                        <textarea
                            name="keterangan"
                            rows="5"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Tuliskan hasil analisis kebutuhan bantuan...">{{ optional($data->kebutuhan)->keterangan }}</textarea>

                    </div>

                    {{-- ========================= --}}
                    {{-- INFORMASI --}}
                    {{-- ========================= --}}

                    <div class="mb-6 rounded-xl bg-blue-50 border border-blue-200 p-4">

                        <h4 class="font-semibold text-blue-700 mb-2">

                            Informasi

                        </h4>

                        <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">

                            <li>Pastikan kebutuhan bantuan sesuai hasil verifikasi lapangan.</li>
                            <li>Status <strong>Ditangani</strong> diberikan jika pengaduan layak ditindaklanjuti.</li>
                            <li>Status <strong>Tidak Direkomendasikan</strong> digunakan apabila pengaduan tidak memenuhi syarat.</li>

                        </ul>

                    </div>

                    <button
                        type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-semibold transition">

                        Simpan Verifikasi

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection