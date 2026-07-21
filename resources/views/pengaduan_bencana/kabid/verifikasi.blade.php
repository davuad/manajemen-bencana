@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>

            <h2 class="text-2xl font-bold text-gray-800">
                Verifikasi Pengaduan
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Lakukan verifikasi terhadap laporan pengaduan bencana dari masyarakat.
            </p>

        </div>

        <a href="{{ route('kabid.pengaduan.index') }}"
            class="inline-flex items-center justify-center bg-gray-600 hover:bg-gray-700 text-white px-5 py-2.5 rounded-xl transition">

            ← Kembali

        </a>

    </div>

    {{-- ERROR --}}
    @if ($errors->any())

        <div class="mb-6 rounded-xl border border-red-300 bg-red-50 p-5">

            <h5 class="font-semibold text-red-700 mb-3">

                Terjadi Kesalahan

            </h5>

            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form
        action="{{ route('kabid.pengaduan.simpan',$data->id) }}"
        method="POST">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- ===================================================== --}}
            {{-- KOLOM KIRI --}}
            {{-- ===================================================== --}}
            <div class="xl:col-span-2 space-y-6">

                {{-- DATA PENGADUAN --}}
                <div class="bg-white border rounded-2xl shadow-sm">

                    <div class="border-b px-6 py-4">

                        <h3 class="font-semibold text-lg text-indigo-700">

                            Data Pengaduan

                        </h3>

                    </div>

                    <div class="p-6">

                        <div class="grid md:grid-cols-2 gap-6">

                            <div>

                                <label class="block text-sm text-gray-500">

                                    Nama Pelapor

                                </label>

                                <p class="mt-1 font-semibold text-gray-800">

                                    {{ $data->user->nama ?? '-' }}

                                </p>

                            </div>

                            <div>

                                <label class="block text-sm text-gray-500">

                                    Kategori Bencana

                                </label>

                                <p class="mt-1 font-semibold text-gray-800">

                                    {{ $data->kategori->nama_kategori ?? '-' }}

                                </p>

                            </div>

                            <div>

                                <label class="block text-sm text-gray-500">

                                    Desa

                                </label>

                                <p class="mt-1 font-semibold text-gray-800">

                                    {{ $data->desa }}

                                </p>

                            </div>

                            <div>

                                <label class="block text-sm text-gray-500">

                                    Tanggal Pengaduan

                                </label>

                                <p class="mt-1 font-semibold text-gray-800">

                                    {{ $data->created_at->format('d M Y H:i') }}

                                </p>

                            </div>

                            @if($data->tanggal_selesai)

                            <div>

                                <label class="block text-sm text-gray-500">

                                    Tanggal Selesai

                                </label>

                                <p class="mt-1 font-semibold text-gray-800">

                                    {{ \Carbon\Carbon::parse($data->tanggal_selesai)->format('d M Y') }}

                                </p>

                            </div>

                            @endif

                            <div class="md:col-span-2">

                                <label class="block text-sm text-gray-500 mb-2">

                                    Deskripsi Kejadian

                                </label>

                                <div class="rounded-xl border bg-gray-50 p-5 leading-relaxed text-gray-700">

                                    {{ $data->deskripsi }}

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- STATUS --}}
                <div class="bg-white border rounded-2xl shadow-sm">

                    <div class="border-b px-6 py-4">

                        <h3 class="font-semibold text-lg text-indigo-700">

                            Status Pengaduan

                        </h3>

                    </div>

                    <div class="p-6">

                        @switch($data->status_pengaduan)

                            @case('BELUM_DITANGANI')

                                <span class="inline-flex items-center px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 font-semibold">

                                    Belum Ditangani

                                </span>

                            @break

                            @case('DITANGANI')

                                <span class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-semibold">

                                    Sedang Ditangani

                                </span>

                            @break

                            @case('SELESAI')

                                <span class="inline-flex items-center px-4 py-2 rounded-full bg-green-100 text-green-700 font-semibold">

                                    Selesai

                                </span>

                            @break

                            @case('TIDAK_DIREKOMENDASIKAN')

                                <span class="inline-flex items-center px-4 py-2 rounded-full bg-red-100 text-red-700 font-semibold">

                                    Tidak Direkomendasikan

                                </span>

                            @break

                        @endswitch

                    </div>

                </div>
                                {{-- ===================================================== --}}
                {{-- DOKUMENTASI --}}
                {{-- ===================================================== --}}
                <div class="bg-white border rounded-2xl shadow-sm">

                    <div class="border-b px-6 py-4">

                        <h3 class="font-semibold text-lg text-indigo-700">

                            Dokumentasi Pengaduan

                        </h3>

                    </div>

                    <div class="p-6">

                        @if($data->foto->count())

                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                                @foreach($data->foto as $foto)

                                    @php
                                        $ext = strtolower(pathinfo($foto->file_foto, PATHINFO_EXTENSION));
                                    @endphp

                                    <div class="border rounded-xl overflow-hidden shadow hover:shadow-lg transition">

                                        {{-- FOTO --}}
                                        @if(in_array($ext,['jpg','jpeg','png','webp']))

                                            <img
                                                src="{{ asset('uploads/pengaduan/'.$foto->file_foto) }}"
                                                class="w-full h-56 object-cover">

                                        {{-- PDF --}}
                                        @elseif($ext=='pdf')

                                            <div class="h-56 flex flex-col justify-center items-center bg-red-50">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="w-20 h-20 text-red-600"
                                                    fill="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path d="M6 2h8l4 4v16H6z"/>

                                                </svg>

                                                <span class="mt-3 text-red-700 font-semibold">

                                                    File PDF

                                                </span>

                                            </div>

                                        @else

                                            <div class="h-56 flex items-center justify-center bg-gray-100">

                                                File Tidak Didukung

                                            </div>

                                        @endif

                                        <div class="p-4">

                                            <div class="grid grid-cols-2 gap-2">

                                                <a
                                                    href="{{ asset('uploads/pengaduan/'.$foto->file_foto) }}"
                                                    target="_blank"
                                                    class="text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-sm transition">

                                                    👁 Lihat

                                                </a>

                                                <a
                                                    href="{{ asset('uploads/pengaduan/'.$foto->file_foto) }}"
                                                    download
                                                    class="text-center bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg text-sm transition">

                                                    ⬇ Download

                                                </a>

                                            </div>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                            {{-- Keterangan hanya sekali --}}
                            <div class="mt-8">

                                <label class="block text-sm text-gray-500 mb-2">

                                    Keterangan Dokumentasi

                                </label>

                                <div class="rounded-xl border bg-gray-50 p-5 text-gray-700 leading-relaxed">

                                    {{ optional($data->foto->first())->keterangan ?: '-' }}

                                </div>

                            </div>

                        @else

                            <div class="rounded-xl border border-yellow-300 bg-yellow-50 p-5 text-yellow-700">

                                Belum ada dokumentasi yang diunggah.

                            </div>

                        @endif

                    </div>

                </div>

            </div>

            {{-- ===================================================== --}}
            {{-- KOLOM KANAN --}}
            {{-- ===================================================== --}}
            <div>
                                <div class="bg-white border rounded-2xl shadow-sm sticky top-5">

                    <div class="border-b px-6 py-4">

                        <h3 class="font-semibold text-lg text-indigo-700">

                            Hasil Verifikasi

                        </h3>

                    </div>

                    <div class="p-6">

                        {{-- ========================= --}}
                        {{-- KEBUTUHAN BANTUAN --}}
                        {{-- ========================= --}}

                        <div class="mb-6">

                            <div class="flex items-center justify-between mb-4">

                                <label class="font-semibold text-gray-700">

                                    Kebutuhan Bantuan

                                </label>

                            </div>

                            <div
                                id="listKebutuhan"
                                class="space-y-3">

                                <label class="flex items-center gap-3">

                                    <input
                                        type="checkbox"
                                        name="dapur_umum"
                                        value="Butuh"
                                        class="rounded border-gray-300 text-indigo-600"
                                        {{ optional($data->kebutuhan)->dapur_umum == 'Butuh' ? 'checked' : '' }}>

                                    <span>Dapur Umum</span>

                                </label>

                                <label class="flex items-center gap-3">

                                    <input
                                        type="checkbox"
                                        name="psikososial"
                                        value="Butuh"
                                        class="rounded border-gray-300 text-indigo-600"
                                        {{ optional($data->kebutuhan)->psikososial == 'Butuh' ? 'checked' : '' }}>

                                    <span>Psikososial</span>

                                </label>

                                <label class="flex items-center gap-3">

                                    <input
                                        type="checkbox"
                                        name="logistik_rentan"
                                        value="Butuh"
                                        class="rounded border-gray-300 text-indigo-600"
                                        {{ optional($data->kebutuhan)->logistik_rentan == 'Butuh' ? 'checked' : '' }}>

                                    <span>Logistik Rentan</span>

                                </label>

                                <label class="flex items-center gap-3">

                                    <input
                                        type="checkbox"
                                        name="logistik_makanan"
                                        value="Butuh"
                                        class="rounded border-gray-300 text-indigo-600"
                                        {{ optional($data->kebutuhan)->logistik_makanan == 'Butuh' ? 'checked' : '' }}>

                                    <span>Logistik Makanan</span>

                                </label>

                                <label class="flex items-center gap-3">

                                    <input
                                        type="checkbox"
                                        name="logistik_penampungan"
                                        value="Butuh"
                                        class="rounded border-gray-300 text-indigo-600"
                                        {{ optional($data->kebutuhan)->logistik_penampungan == 'Butuh' ? 'checked' : '' }}>

                                    <span>Logistik Penampungan</span>

                                </label>

                            </div>

                            {{-- INPUT TAMBAH --}}
                            <div class="mt-5">

                                <div class="flex gap-2">

                                    <input
                                        type="text"
                                        id="inputKebutuhan"
                                        class="flex-1 rounded-lg border-gray-300"
                                        placeholder="Masukkan kebutuhan baru...">

                                    <button
                                        type="button"
                                        id="tambahKebutuhan"
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 rounded-lg">

                                        Tambah

                                    </button>

                                </div>

                            </div>

                        </div>

                        {{-- STATUS --}}

                        <div class="mb-6">

                            <label class="block text-sm font-semibold text-gray-700 mb-2">

                                Status Pengaduan

                            </label>

                            <select
                                name="status_pengaduan"
                                class="w-full rounded-xl border-gray-300">

                                <option value="BELUM_DITANGANI"
                                    {{ $data->status_pengaduan=='BELUM_DITANGANI' ? 'selected':'' }}>
                                    Belum Ditangani
                                </option>

                                <option value="DITANGANI"
                                    {{ $data->status_pengaduan=='DITANGANI' ? 'selected':'' }}>
                                    Ditangani
                                </option>

                                <option value="TIDAK_DIREKOMENDASIKAN"
                                    {{ $data->status_pengaduan=='TIDAK_DIREKOMENDASIKAN' ? 'selected':'' }}>
                                    Tidak Direkomendasikan
                                </option>

                            </select>

                        </div>

                        {{-- KETERANGAN --}}

                        <div class="mb-6">

                            <label class="block text-sm font-semibold text-gray-700 mb-2">

                                Keterangan Verifikasi

                            </label>

                            <textarea
                                name="keterangan"
                                rows="5"
                                class="w-full rounded-xl border-gray-300"
                                placeholder="Tuliskan hasil verifikasi...">{{ optional($data->kebutuhan)->keterangan }}</textarea>

                        </div>

                        {{-- INFORMASI --}}

                        <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 mb-6">

                            <h4 class="font-semibold text-blue-700 mb-2">

                                Informasi

                            </h4>

                            <ul class="text-sm text-blue-700 list-disc list-inside space-y-2">

                                <li>Pastikan kebutuhan sesuai hasil survei lapangan.</li>

                                <li>Status <strong>Ditangani</strong> digunakan apabila laporan layak ditindaklanjuti.</li>

                                <li>Status <strong>Tidak Direkomendasikan</strong> digunakan apabila laporan tidak memenuhi syarat.</li>

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

        </div>

    </form>

</div>

@endsection
<script>

document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('inputKebutuhan');
    const tombol = document.getElementById('tambahKebutuhan');
    const list = document.getElementById('listKebutuhan');

    tombol.addEventListener('click', tambahKebutuhan);

    input.addEventListener('keypress', function(e){

        if(e.key === 'Enter'){

            e.preventDefault();

            tambahKebutuhan();

        }

    });

    function tambahKebutuhan(){

        let nama = input.value.trim();

        if(nama === ''){

            input.focus();

            return;

        }

        let id = nama
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g,'_')
                    .replace(/^_+|_+$/g,'');

        const item = document.createElement('div');

        item.className = 'flex items-center justify-between gap-3 kebutuhan-item';

        item.innerHTML = `
            <label class="flex items-center gap-3 flex-1">

                <input
                    type="checkbox"
                    name="kebutuhan_tambahan[]"
                    value="${nama}"
                    checked
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">

                <span>${nama}</span>

            </label>

            <button
                type="button"
                class="hapusKebutuhan text-red-600 hover:text-red-700">

                Hapus

            </button>
        `;

        list.appendChild(item);

        input.value = '';

        input.focus();

    }

    document.addEventListener('click', function(e){

        if(e.target.classList.contains('hapusKebutuhan')){

            e.target.closest('.kebutuhan-item').remove();

        }

    });

});
</script>