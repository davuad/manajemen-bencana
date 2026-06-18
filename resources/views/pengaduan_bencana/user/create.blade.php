@extends('layouts.app')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

```
{{-- Header --}}
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">

    <div>
        <h2 class="text-2xl font-bold text-gray-800">
            Form Pengaduan Bencana
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Silakan lengkapi data pengaduan sesuai kondisi yang terjadi.
        </p>
    </div>

    <a href="{{ route('user.pengaduan.index') }}"
        class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">

        Kembali

    </a>

</div>

{{-- Error --}}
@if ($errors->any())

    <div class="mb-6 rounded-xl border border-red-300 bg-red-50 p-4">

        <h5 class="font-semibold text-red-700 mb-2">
            Terjadi Kesalahan
        </h5>

        <ul class="list-disc list-inside text-sm text-red-600">

            @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif

<form action="{{ route('user.pengaduan.store') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    {{-- DATA PELAPOR --}}
    <div class="border rounded-xl p-6 mb-6">

        <h3 class="text-lg font-semibold text-green-700 mb-5">
            Data Pelapor
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Pelapor
                </label>

                <input
                    type="text"
                    value="{{ Auth::user()->nama }}"
                    readonly
                    class="w-full rounded-xl border-gray-300 bg-gray-100">

            </div>

        </div>

    </div>

    {{-- DATA PENGADUAN --}}
    <div class="border rounded-xl p-6 mb-6">

        <h3 class="text-lg font-semibold text-indigo-700 mb-5">
            Data Pengaduan
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Kategori Bencana
                </label>

                <select
                    name="kategori_id"
                    required
                    class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                    <option value="">
                        Pilih Kategori
                    </option>

                    @foreach($kategori as $item)

                        <option value="{{ $item->id }}"
                            {{ old('kategori_id') == $item->id ? 'selected' : '' }}>

                            {{ $item->nama_kategori }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Desa
                </label>

                <input
                    type="text"
                    name="desa"
                    value="{{ old('desa') }}"
                    required
                    class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

            </div>

        </div>

        <div class="mt-6">

            <label class="block text-sm font-medium text-gray-700 mb-2">
                Deskripsi Kejadian
            </label>

            <textarea
                name="deskripsi"
                rows="6"
                required
                class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Jelaskan kondisi bencana yang terjadi...">{{ old('deskripsi') }}</textarea>

        </div>

    </div>

    {{-- DOKUMENTASI --}}
    <div class="border rounded-xl p-6 mb-6">

        <h3 class="text-lg font-semibold text-sky-700 mb-5">
            Dokumentasi Bencana
        </h3>

        <div class="mb-5">

            <label class="block text-sm font-medium text-gray-700 mb-2">
                Upload Foto
            </label>

            <input
                type="file"
                name="foto[]"
                multiple
                class="block w-full rounded-xl border border-gray-300 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:text-white hover:file:bg-indigo-700">

            <p class="text-sm text-gray-500 mt-2">
                Anda dapat mengunggah lebih dari satu foto.
            </p>

        </div>

        <div>

            <label class="block text-sm font-medium text-gray-700 mb-2">
                Keterangan Foto
            </label>

            <input
                type="text"
                name="keterangan"
                value="{{ old('keterangan') }}"
                placeholder="Contoh: Kondisi rumah terdampak banjir"
                class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

        </div>

    </div>

    {{-- Informasi --}}
    <div class="mb-6 rounded-xl border border-yellow-300 bg-yellow-50 p-4">

        <h5 class="font-semibold text-yellow-700 mb-2">
            Perhatian
        </h5>

        <ul class="list-disc list-inside text-sm text-yellow-700 space-y-1">

            <li>Pastikan data yang dilaporkan sesuai kondisi sebenarnya.</li>

            <li>Pengaduan akan diverifikasi oleh Kabid.</li>

            <li>Status pengaduan dapat dipantau melalui menu <strong>Pengaduan Saya</strong>.</li>

        </ul>

    </div>

    {{-- Tombol --}}
    <div class="flex justify-end gap-3">

        <a href="{{ route('user.pengaduan.index') }}"
            class="px-5 py-2.5 rounded-xl bg-gray-500 hover:bg-gray-600 text-white transition">

            Batal

        </a>

        <button
            type="submit"
            class="px-5 py-2.5 rounded-xl bg-indigo-700 hover:bg-indigo-800 text-white transition">

            Kirim Pengaduan

        </button>

    </div>

</form>
</div>

@endsection
