@extends('layouts.app')

@section('content')
@php
    $prefix = auth()->user()->hasRole('petugas') ? 'petugas' : 'admin';
@endphp
<div class="bg-slate-200 min-h-screen p-4 md:p-6">

    {{-- Header --}}
    <div class="bg-white px-6 py-4 flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-black">Form Anak Terpisah</h1>

        <a href="{{ route($prefix.'.anak_terpisah.index') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg shadow">
            Kembali
        </a>
    </div>

    {{-- Error --}}
    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-300 bg-red-50 p-4">
            <ul class="list-disc list-inside text-red-600 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route($prefix.'.anak_terpisah.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

      <div class="bg-white p-6 md:p-10">
    {{-- BAGIAN ATAS --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">

        {{-- FOTO --}}
        <div class="md:col-span-3 flex flex-col items-center">

            <div class="w-48 h-48 border-4 border-black flex items-center justify-center overflow-hidden">
                <img id="preview-foto"
                    class="hidden w-full h-full object-cover">

                <div id="placeholder-foto" class="text-6xl">
                    👤
                </div>
            </div>

            <label class="mt-3 cursor-pointer bg-indigo-600 text-white px-4 py-2 rounded-lg">
                Upload Foto
                <input type="file"
                    name="foto_anak"
                    id="foto_anak"
                    class="opacity-0 absolute w-0 h-0"
                    accept=".jpg,.jpeg,.png"
                    required>
            </label>

        </div>

        {{-- FORM KANAN --}}
        <div class="md:col-span-9 space-y-4">

            <div>
                <label class="block font-medium">Bencana *</label>

                <select name="bencana_id"
                        class="w-full border rounded-lg p-3"
                        required>

                    <option value="">Pilih Bencana</option>

                    @foreach($bencana as $b)
                        <option value="{{ $b->id }}"
                            {{ old('bencana_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->nama_bencana }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div>
                <label class="block font-medium">Nama Anak *</label>
                <input type="text"
                    name="nama_anak"
                    value="{{ old('nama_anak') }}"
                    class="w-full border rounded-lg p-3"
                    required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block font-medium">Nama Bapak</label>
                    <input type="text"
                        name="nama_bapak"
                        value="{{ old('nama_bapak') }}"
                        class="w-full border rounded-lg p-3">
                </div>

                <div>
                    <label class="block font-medium">Nama Ibu</label>
                    <input type="text"
                        name="nama_ibu"
                        value="{{ old('nama_ibu') }}"
                        class="w-full border rounded-lg p-3">
                </div>

            </div>

        </div>

    </div>

    <hr class="my-8">

    {{-- BAGIAN BAWAH --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        {{-- KOLOM KIRI --}}
        <div class="space-y-4 md:border-r md:pr-8">

            <div>
                <label class="block font-medium mb-2">Jenis Kelamin</label>

                <div class="flex gap-6">
                    <label>
                        <input type="radio" name="jenis_kelamin" value="L">
                        Laki-Laki
                    </label>

                    <label>
                        <input type="radio" name="jenis_kelamin" value="P">
                        Perempuan
                    </label>
                </div>
            </div>

            <div>
                <label class="block font-medium">Umur</label>
                <input type="number"
                    name="umur"
                    value="{{ old('umur') }}"
                    class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block font-medium">Tanggal Lahir</label>
                <input type="date"
                    name="tanggal_lahir"
                    class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block font-medium">Tanggal Ditemukan</label>
                <input type="date"
                    name="tanggal_ditemukan"
                    class="w-full border rounded-lg p-3"
                    required>
            </div>

        </div>

        {{-- KOLOM KANAN --}}
        <div class="space-y-4">

            <div>
                <label class="block font-medium">Lokasi Ditemukan</label>
                <textarea
                    name="lokasi_ditemukan"
                    rows="3"
                    class="w-full border rounded-lg p-3"
                    required></textarea>
            </div>

            <div>
                <label class="block font-medium">Alamat Asal</label>
                <textarea
                    name="alamat_asal"
                    rows="3"
                    class="w-full border rounded-lg p-3"></textarea>
            </div>

            <div>
                <label class="block font-medium">Kontak Keluarga</label>
                <input type="text"
                    name="kontak_keluarga"
                    class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block font-medium">Status Anak</label>
                <select name="status_anak"
                    class="w-full border rounded-lg p-3">

                    <option value="belum_dijemput">Belum Dijemput</option>
                    <option value="dalam_proses">Dalam Proses</option>
                    <option value="sudah_dijemput">Sudah Dijemput</option>

                </select>
            </div>

        </div>

    </div>

        {{-- BUTTON --}}
    <div class="flex justify-end gap-3 mt-8">

        <a href="{{ route($prefix.'.anak_terpisah.index') }}"
            class="px-4 py-2 bg-gray-300 rounded-lg">
            Batal
        </a>

        <button type="submit"
            class="px-6 py-2 bg-indigo-700 text-white rounded-lg">
            Simpan Data
        </button>

    </div>

</div> {{-- tutup bg-white --}}

</form>

</div> {{-- tutup bg-slate-200 --}}

{{-- Preview Foto --}}
<script>
document.getElementById('foto_anak').addEventListener('change', function (e) {
    const file = e.target.files[0];

    if (file) {
        document.getElementById('preview-foto').src = URL.createObjectURL(file);
        document.getElementById('preview-foto').classList.remove('hidden');
        document.getElementById('placeholder-foto').classList.add('hidden');
    }
});
</script>
@endsection