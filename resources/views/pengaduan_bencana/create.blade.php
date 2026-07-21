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

            <a href="{{ route('admin.pengaduan_bencana.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">

                Kembali

            </a>

        </div>

        {{-- FORM --}}
        <form action="{{ route('admin.pengaduan_bencana.store') }}" method="POST" enctype="multipart/form-data">

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

                        <select name="kategori_id" class="w-full border rounded-lg px-4 py-2" required>

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

                        <select class="w-full border rounded-lg px-4 py-2 bg-gray-100" disabled>

                            @foreach($user as $u)

                                <option value="{{ $u->id }}" {{ auth()->id() == $u->id ? 'selected' : '' }}>

                                    {{ $u->nama }}

                                </option>

                            @endforeach

                        </select>

                        <input type="hidden" name="user_id" value="{{ auth()->id() ?? 1 }}">

                    </div>

                </div>

                {{-- DESA --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Desa
                    </label>

                    <select name="desa_id"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-300 focus:outline-none"
                        required>

                        <option value="">-- Pilih Desa --</option>

                        @foreach($desa as $d)
                            <option value="{{ $d->id }}" {{ old('desa_id') == $d->id ? 'selected' : '' }}>
                                {{ $d->nama_desa }}
                            </option>
                        @endforeach

                    </select>

                    @error('desa_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DESKRIPSI --}}
                <div class="mt-5">

                    <label class="block mb-2 text-sm font-medium">
                        Deskripsi Kejadian
                    </label>

                    <textarea name="deskripsi" rows="4" class="w-full border rounded-lg px-4 py-2"
                        placeholder="Jelaskan kejadian bencana..." required></textarea>

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

                        <div class="bg-gray-50 rounded-xl border p-5">

                            <div class="mb-4">

                                <h4 class="text-lg font-semibold text-gray-800 flex items-center gap-2">

                                    <x-heroicon-o-paper-clip class="w-5 h-5 text-indigo-600" />

                                    Lampiran Pengaduan

                                </h4>

                                <p class="text-sm text-gray-500 mt-1">

                                    Upload foto dokumentasi maupun file PDF pendukung.

                                </p>

                            </div>

                            <div id="lampiran-container">

                                <div class="lampiran-item flex items-center gap-3 mb-3">

                                    <input type="file" name="lampiran[]" accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-sm text-gray-700
                               file:mr-4 file:px-4 file:py-2
                               file:rounded-lg
                               file:border-0
                               file:bg-indigo-600
                               file:text-white
                               hover:file:bg-indigo-700">

                                    <button type="button"
                                        class="btnTambah bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg">

                                        +

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div>

                        <label class="block mb-2 text-sm font-medium">
                            Keterangan Foto
                        </label>

                        <input type="text" name="keterangan" class="w-full border rounded-lg px-4 py-2"
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
                                <input type="radio" name="dapur_umum" value="Butuh">

                                Butuh
                            </label>

                            <label>
                                <input type="radio" name="dapur_umum" value="Tidak" checked>

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
                                <input type="radio" name="psikososial" value="Butuh">

                                Butuh
                            </label>

                            <label>
                                <input type="radio" name="psikososial" value="Tidak" checked>

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
                                <input type="radio" name="logistik_rentan" value="Butuh">

                                Butuh
                            </label>

                            <label>
                                <input type="radio" name="logistik_rentan" value="Tidak" checked>

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
                                <input type="radio" name="logistik_makanan" value="Butuh">

                                Butuh
                            </label>

                            <label>
                                <input type="radio" name="logistik_makanan" value="Tidak" checked>

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
                                <input type="radio" name="logistik_penampungan" value="Butuh">

                                Butuh
                            </label>

                            <label>
                                <input type="radio" name="logistik_penampungan" value="Tidak" checked>

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

                    <textarea name="keterangan_kebutuhan" rows="3" class="w-full border rounded-lg px-4 py-2"
                        placeholder="Tambahan kebutuhan darurat..."></textarea>

                </div>

            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end gap-3">

                <a href="{{ route('admin.pengaduan_bencana.index') }}"
                    class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg">

                    Batal

                </a>

                <button type="submit" class="bg-indigo-700 text-white px-5 py-2 rounded-lg">

                    Simpan Pengaduan

                </button>

            </div>

        </form>

    </div>

@endsection

<script>

    document.addEventListener("click", function (e) {

        if (e.target.classList.contains('btnTambah')) {

            let html = `
        <div class="lampiran-item flex items-center gap-3 mb-3">

            <input
                type="file"
                name="lampiran[]"
                accept=".jpg,.jpeg,.png,.pdf"
                class="block w-full text-sm text-gray-700
                file:mr-4 file:px-4 file:py-2
                file:rounded-lg
                file:border-0
                file:bg-indigo-600
                hover:file:bg-indigo-700
                file:text-white">

            <button
                type="button"
                class="btnHapus bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg">

                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.5"
                     stroke="currentColor"
                     class="w-5 h-5">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M6 18L18 6M6 6l12 12"/>

                </svg>

            </button>

        </div>`;

            document
                .getElementById("lampiran-container")
                .insertAdjacentHTML("beforeend", html);

        }

        if (e.target.closest('.btnHapus')) {

            e.target.closest('.lampiran-item').remove();

        }

    });

</script>