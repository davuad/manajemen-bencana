@extends('layouts.app')

@section('content')

    <div class="max-w-6xl mx-auto">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">

            <div>

                <h2 class="text-2xl font-bold text-gray-800">
                    Form Pengaduan Bencana
                </h2>

                <p class="text-gray-500 mt-1">
                    Lengkapi data pengaduan sesuai kondisi di lapangan.
                </p>

            </div>

            <a href="{{ route('user.pengaduan.index') }}"
                class="mt-3 md:mt-0 bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-lg">

                Kembali

            </a>

        </div>

        {{-- Error --}}
        @if ($errors->any())

            <div class="mb-6 bg-red-50 border border-red-300 rounded-lg p-4">

                <h5 class="font-semibold text-red-700 mb-2">
                    Terjadi Kesalahan
                </h5>

                <ul class="list-disc list-inside text-red-600 text-sm">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        <form action="{{ route('user.pengaduan.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            {{-- DATA PELAPOR --}}
            <div class="bg-white rounded-xl shadow border mb-6">

                <div class="border-b px-6 py-4">

                    <h4 class="font-semibold text-green-700">
                        Data Pelapor
                    </h4>

                </div>

                <div class="p-6">

                    <div class="grid md:grid-cols-2 gap-5">

                        <div>

                            <label class="block mb-2 text-sm font-medium">
                                Nama Pelapor
                            </label>

                            <input type="text" readonly value="{{ Auth::user()->nama }}"
                                class="w-full rounded-lg bg-gray-100 border-gray-300">

                        </div>

                    </div>

                </div>

            </div>

            {{-- DATA PENGADUAN --}}
            <div class="bg-white rounded-xl shadow border mb-6">

                <div class="border-b px-6 py-4">

                    <h4 class="font-semibold text-indigo-700">
                        Data Pengaduan
                    </h4>

                </div>

                <div class="p-6">

                    <div class="grid md:grid-cols-2 gap-5">

                        <div>

                            <label class="block mb-2 text-sm font-medium">
                                Kategori Bencana
                            </label>

                            <select name="kategori_id" class="w-full rounded-lg border-gray-300">

                                <option value="">
                                    -- Pilih Kategori --
                                </option>

                                @foreach($kategori as $item)

                                    <option value="{{ $item->id }}" {{ old('kategori_id') == $item->id ? 'selected' : '' }}>

                                        {{ $item->nama_kategori }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div>

                            <label class="block mb-2 text-sm font-medium">
                                Desa
                            </label>

                            <select name="desa" class="w-full rounded-lg border-gray-300">

                                <option value="">
                                    -- Pilih Desa --
                                </option>

                                @foreach($desa as $item)

                                    <option value="{{ $item->nama_desa }}" {{ old('desa') == $item->nama_desa ? 'selected' : '' }}>

                                        {{ $item->nama_desa }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                    <div class="mt-5">

                        <label class="block mb-2 text-sm font-medium">
                            Deskripsi Kejadian
                        </label>

                        <textarea rows="6" name="deskripsi" class="w-full rounded-lg border-gray-300"
                            placeholder="Jelaskan kronologi bencana...">{{ old('deskripsi') }}</textarea>

                    </div>

                </div>

            </div>

{{-- DOKUMENTASI --}}
<div class="bg-white rounded-xl shadow border mb-6">

    <div class="border-b px-6 py-4 flex justify-between items-center">

        <h4 class="font-semibold text-blue-700">
            Dokumentasi
        </h4>

        <button
            type="button"
            id="tambahFile"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">

            + Tambah File

        </button>

    </div>

    <div class="p-6">

        <div id="file-wrapper">

            <div class="file-item flex items-center gap-3 mb-3">

                <input
                    type="file"
                    name="foto[]"
                    accept=".jpg,.jpeg,.png,.pdf"
                    class="flex-1 rounded-lg border border-gray-300">

                <button
                    type="button"
                    class="hapus bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg">

                    Hapus

                </button>

            </div>

        </div>

        <div class="mt-5">

            <label class="block mb-2 text-sm font-medium">
                Keterangan Dokumentasi
            </label>

            <textarea
                name="keterangan"
                rows="3"
                class="w-full rounded-lg border-gray-300"
                placeholder="Contoh: Dokumentasi kondisi rumah warga yang terdampak banjir.">{{ old('keterangan') }}</textarea>

        </div>

        <p class="text-sm text-gray-500 mt-3">
            Format: JPG, JPEG, PNG, PDF (Maksimal 5 MB setiap file).
        </p>

    </div>

</div>
            {{-- INFORMASI --}}
            <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-5 mb-6">

                <h4 class="font-semibold text-yellow-700 mb-3">
                    Informasi
                </h4>

                <ul class="list-disc list-inside text-sm text-yellow-700 space-y-2">

                    <li>
                        Pastikan data yang dikirim sesuai kondisi sebenarnya.
                    </li>

                    <li>
                        Pengaduan akan diverifikasi oleh Kabid.
                    </li>

                    <li>
                        Status pengaduan dapat dipantau pada menu
                        <strong>Pengaduan Saya</strong>.
                    </li>

                    <li>
                        Anda dapat mengunggah beberapa foto maupun file PDF sekaligus sebagai dokumentasi pendukung.
                    </li>

                    <li>
                        Notifikasi WhatsApp hanya akan dikirim apabila Administrator telah mengaktifkan dan mengatur nomor
                        tujuan pada menu
                        <strong>Pengaturan Notifikasi</strong>.
                    </li>

                </ul>

            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end gap-3">

                <a href="{{ route('user.pengaduan.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg transition">

                    Batal

                </a>

                <button type="submit" class="bg-indigo-700 hover:bg-indigo-800 text-white px-6 py-2 rounded-lg transition">

                    Kirim Pengaduan

                </button>

            </div>

        </form>

    </div>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('tambahFile').addEventListener('click', function () {

        let html = `
            <div class="file-item flex items-center gap-3 mb-3">

                <input
                    type="file"
                    name="foto[]"
                    accept=".jpg,.jpeg,.png,.pdf"
                    class="flex-1 rounded-lg border border-gray-300">

                <button
                    type="button"
                    class="hapus bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg">

                    Hapus

                </button>

            </div>
        `;

        document.getElementById('file-wrapper')
            .insertAdjacentHTML('beforeend', html);

    });

    document.addEventListener('click', function(e){

        if(e.target.classList.contains('hapus')){

            const items = document.querySelectorAll('.file-item');

            if(items.length > 1){

                e.target.closest('.file-item').remove();

            }else{

                alert('Minimal harus ada satu file.');

            }

        }

    });

});
</script>