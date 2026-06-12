
@extends('layouts.app')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">

        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Edit Pengaduan #{{ $data->id }}
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Perbarui data pengaduan bencana masyarakat
            </p>
        </div>

        <a href="/pengaduan"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-medium transition">

            Kembali

        </a>

    </div>

    <form action="/pengaduan/{{ $data->id }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        {{-- DATA UTAMA --}}
        <div class="bg-gray-50 rounded-2xl border border-gray-100 p-5 mb-6">

            <h4 class="text-lg font-semibold text-gray-800 mb-5">
                Data Pengaduan
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- KATEGORI --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori Bencana
                    </label>

                    <select name="kategori_id"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-300 focus:outline-none">

                        @foreach($kategori as $k)

                            <option value="{{ $k->id }}"
                                {{ $data->kategori_id == $k->id ? 'selected' : '' }}>

                                {{ $k->nama_kategori }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- USER --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pelapor
                    </label>

                    <select disabled
                            class="w-full border border-gray-300 bg-gray-100 rounded-xl px-4 py-2.5">

                        @foreach($user as $u)

                            <option value="{{ $u->id }}"
                                {{ $data->user_id == $u->id ? 'selected' : '' }}>

                                {{ $u->nama }}

                            </option>

                        @endforeach

                    </select>

                    <input type="hidden"
                           name="user_id"
                           value="{{ $data->user_id }}">

                </div>

            </div>

            {{-- DESA --}}
            <div class="mt-5">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Lokasi / Desa
                </label>

                <input type="text"
                       name="desa"
                       value="{{ $data->desa }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-300 focus:outline-none">

            </div>

            {{-- DESKRIPSI --}}
            <div class="mt-5">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi Kejadian
                </label>

                <textarea name="deskripsi"
                          rows="4"
                          class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-300 focus:outline-none">{{ $data->deskripsi }}</textarea>

            </div>

        </div>

        {{-- STATUS --}}
        <div class="bg-gray-50 rounded-2xl border border-gray-100 p-5 mb-6">

            <h4 class="text-lg font-semibold text-gray-800 mb-5">
                Status Pengaduan
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- STATUS --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>

                    <select name="status_pengaduan"
                            id="status_pengaduan"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-300 focus:outline-none">

                        <option value="BELUM_DITANGANI"
                            {{ $data->status_pengaduan == 'BELUM_DITANGANI' ? 'selected' : '' }}>

                            Belum Ditangani

                        </option>

                        <option value="DITANGANI"
                            {{ $data->status_pengaduan == 'DITANGANI' ? 'selected' : '' }}>

                            Ditangani

                        </option>

                        <option value="SELESAI"
                            {{ $data->status_pengaduan == 'SELESAI' ? 'selected' : '' }}>

                            Selesai

                        </option>

                        <option value="TIDAK_DIREKOMENDASIKAN"
                            {{ $data->status_pengaduan == 'TIDAK_DIREKOMENDASIKAN' ? 'selected' : '' }}>

                            Ditolak

                        </option>

                    </select>

                </div>

                {{-- TANGGAL --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Selesai
                    </label>

                    <input type="datetime-local"
                           name="tanggal_selesai"
                           id="tanggal_selesai"
                           value="{{ $data->tanggal_selesai }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-300 focus:outline-none">

                </div>

            </div>

        </div>

        {{-- FOTO --}}
        <div class="bg-gray-50 rounded-2xl border border-gray-100 p-5 mb-6">

            <h4 class="text-lg font-semibold text-gray-800 mb-5">
                Foto Pengaduan
            </h4>

            @if($data->foto && $data->foto->count() > 0)

                <div class="mb-5">

                    <img src="{{ asset('foto/'.$data->foto[0]->file_foto) }}"
                         class="w-40 h-40 object-cover rounded-xl border shadow-sm">

                </div>

            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Upload Foto Baru
                    </label>

                    <input type="file"
                           name="foto"
                           id="file_foto"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5">

                </div>

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan Foto
                    </label>

                    <input type="text"
                           name="keterangan_foto"
                           value="{{ $data->foto[0]->keterangan ?? '' }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5">

                </div>

            </div>

        </div>

        {{-- KEBUTUHAN --}}
        <div class="bg-gray-50 rounded-2xl border border-gray-100 p-5 mb-6">

            <h4 class="text-lg font-semibold text-gray-800 mb-5">
                Kebutuhan Darurat
            </h4>

            @php
                $k = $data->kebutuhan;
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

                @php
                    $fields = [
                        'dapur_umum' => 'Dapur Umum',
                        'psikososial' => 'Psikososial',
                        'logistik_rentan' => 'Logistik Rentan',
                        'logistik_makanan' => 'Logistik Makanan',
                        'logistik_penampungan' => 'Logistik Penampungan'
                    ];
                @endphp

                @foreach($fields as $name => $label)

                <div class="bg-white border rounded-xl p-4">

                    <label class="font-medium text-gray-700 block mb-3">

                        {{ $label }}

                    </label>

                    <div class="flex gap-4 text-sm">

                        <label class="flex items-center gap-2">

                            <input type="radio"
                                   name="{{ $name }}"
                                   value="Butuh"
                                   {{ ($k && $k->$name == 'Butuh') ? 'checked' : '' }}>

                            Butuh

                        </label>

                        <label class="flex items-center gap-2">

                            <input type="radio"
                                   name="{{ $name }}"
                                   value="Tidak"
                                   {{ (!$k || $k->$name != 'Butuh') ? 'checked' : '' }}>

                            Tidak

                        </label>

                    </div>

                </div>

                @endforeach

            </div>

            {{-- KETERANGAN --}}
            <div class="mt-5">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan Tambahan
                </label>

                <textarea name="keterangan_kebutuhan"
                          rows="3"
                          class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-300 focus:outline-none">{{ $k->keterangan ?? '' }}</textarea>

            </div>

        </div>

        {{-- BUTTON --}}
        <div class="flex justify-end gap-3">

            <a href="/pengaduan"
               class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-medium transition">

                Batal

            </a>

            <button type="submit"
                    class="bg-indigo-700 hover:bg-indigo-800 text-white px-5 py-2.5 rounded-xl text-sm font-medium shadow-sm transition">

                Simpan Perubahan

            </button>

        </div>

    </form>

</div>

{{-- SCRIPT --}}
<script>

    document.getElementById('status_pengaduan').addEventListener('change', function () {

        const inputTanggal = document.getElementById('tanggal_selesai');

        if (this.value === 'SELESAI') {

            const now = new Date();

            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());

            inputTanggal.value = now.toISOString().slice(0, 16);

        }

    });

</script>

@endsection

