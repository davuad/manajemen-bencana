
@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">

        <div>
            <h2 class="text-xl font-bold">
                TAMBAH PENGADUAN
            </h2>

            <p class="text-gray-500 text-sm">
                Tambahkan laporan pengaduan bencana baru
            </p>
        </div>

        <a href="/pengaduan"
           class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">

            Kembali

        </a>

    </div>

    {{-- FORM --}}
    <form action="/pengaduan/store"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        {{-- DATA UTAMA --}}
        <div class="mb-8">

            <h4 class="font-semibold text-lg mb-4">
                Data Pengaduan
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- KATEGORI --}}
                <div>

                    <label class="block mb-2 text-sm font-medium">
                        Kategori Bencana
                    </label>

                    <select name="kategori_id"
                            class="w-full border rounded-lg px-4 py-2"
                            required>

                        <option value="">
                            Pilih Kategori
                        </option>

                        @foreach($kategori as $k)

                            <option value="{{ $k->id }}">

                                {{ $k->nama_kategori }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- USER --}}
                <div>

                    <label class="block mb-2 text-sm font-medium">
                        Pelapor
                    </label>

                    <select class="w-full border rounded-lg px-4 py-2 bg-gray-100"
                            disabled>

                        @foreach($users as $u)

                            <option value="{{ $u->id }}"
                                {{ auth()->id() == $u->id ? 'selected' : '' }}>

                                {{ $u->nama }}

                            </option>

                        @endforeach

                    </select>

                    <input type="hidden"
                           name="user_id"
                           value="{{ auth()->id() ?? 1 }}">

                </div>

            </div>

            {{-- DESA --}}
            <div class="mt-5">

                <label class="block mb-2 text-sm font-medium">
                    Desa
                </label>

                <textarea name="desa"
                          rows="2"
                          class="w-full border rounded-lg px-4 py-2"
                          placeholder="Masukkan lokasi desa..."
                          required></textarea>

            </div>

            {{-- DESKRIPSI --}}
            <div class="mt-5">

                <label class="block mb-2 text-sm font-medium">
                    Deskripsi Kejadian
                </label>

                <textarea name="deskripsi"
                          rows="4"
                          class="w-full border rounded-lg px-4 py-2"
                          placeholder="Jelaskan kejadian bencana..."
                          required></textarea>

            </div>

        </div>

        {{-- FOTO --}}
        <div class="mb-8">

            <h4 class="font-semibold text-lg mb-4">
                Foto Kejadian
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>

                    <label class="block mb-2 text-sm font-medium">
                        Upload Foto
                    </label>

                    <input type="file"
                           name="foto[]"
                           multiple
                           class="w-full border rounded-lg px-4 py-2">

                </div>

                <div>

                    <label class="block mb-2 text-sm font-medium">
                        Keterangan Foto
                    </label>

                    <input type="text"
                           name="keterangan"
                           class="w-full border rounded-lg px-4 py-2"
                           placeholder="Keterangan foto">

                </div>

            </div>

        </div>

        {{-- KEBUTUHAN --}}
        <div class="mb-8">

            <h4 class="font-semibold text-lg mb-4">
                Kebutuhan Darurat
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

                {{-- DAPUR UMUM --}}
                <div class="border rounded-lg p-4">

                    <label class="font-medium block mb-3">
                        Dapur Umum
                    </label>

                    <div class="flex gap-4">

                        <label>
                            <input type="radio"
                                   name="dapur_umum"
                                   value="Butuh">

                            Butuh
                        </label>

                        <label>
                            <input type="radio"
                                   name="dapur_umum"
                                   value="Tidak"
                                   checked>

                            Tidak
                        </label>

                    </div>

                </div>

                {{-- PSIKOSOSIAL --}}
                <div class="border rounded-lg p-4">

                    <label class="font-medium block mb-3">
                        Psikososial
                    </label>

                    <div class="flex gap-4">

                        <label>
                            <input type="radio"
                                   name="psikososial"
                                   value="Butuh">

                            Butuh
                        </label>

                        <label>
                            <input type="radio"
                                   name="psikososial"
                                   value="Tidak"
                                   checked>

                            Tidak
                        </label>

                    </div>

                </div>

                {{-- LOGISTIK RENTAN --}}
                <div class="border rounded-lg p-4">

                    <label class="font-medium block mb-3">
                        Logistik Rentan
                    </label>

                    <div class="flex gap-4">

                        <label>
                            <input type="radio"
                                   name="logistik_rentan"
                                   value="Butuh">

                            Butuh
                        </label>

                        <label>
                            <input type="radio"
                                   name="logistik_rentan"
                                   value="Tidak"
                                   checked>

                            Tidak
                        </label>

                    </div>

                </div>

                {{-- LOGISTIK MAKANAN --}}
                <div class="border rounded-lg p-4">

                    <label class="font-medium block mb-3">
                        Logistik Makanan
                    </label>

                    <div class="flex gap-4">

                        <label>
                            <input type="radio"
                                   name="logistik_makanan"
                                   value="Butuh">

                            Butuh
                        </label>

                        <label>
                            <input type="radio"
                                   name="logistik_makanan"
                                   value="Tidak"
                                   checked>

                            Tidak
                        </label>

                    </div>

                </div>

                {{-- TENDA --}}
                <div class="border rounded-lg p-4">

                    <label class="font-medium block mb-3">
                        Logistik Penampungan
                    </label>

                    <div class="flex gap-4">

                        <label>
                            <input type="radio"
                                   name="logistik_penampungan"
                                   value="Butuh">

                            Butuh
                        </label>

                        <label>
                            <input type="radio"
                                   name="logistik_penampungan"
                                   value="Tidak"
                                   checked>

                            Tidak
                        </label>

                    </div>

                </div>

            </div>

            {{-- KETERANGAN --}}
            <div class="mt-5">

                <label class="block mb-2 text-sm font-medium">
                    Keterangan Kebutuhan
                </label>

                <textarea name="keterangan_kebutuhan"
                          rows="3"
                          class="w-full border rounded-lg px-4 py-2"
                          placeholder="Tambahan kebutuhan darurat..."></textarea>

            </div>

        </div>

        {{-- BUTTON --}}
        <div class="flex justify-end gap-3">

            <a href="/pengaduan"
               class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg">

                Batal

            </a>

            <button type="submit"
                    class="bg-indigo-700 text-white px-5 py-2 rounded-lg">

                Simpan Pengaduan

            </button>

        </div>

    </form>

</div>

@endsection
