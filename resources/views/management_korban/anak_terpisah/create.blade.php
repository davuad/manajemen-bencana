@extends('layouts.app')

@section('content')
<div class="bg-slate-200 min-h-screen p-4 md:p-6">

    {{-- Header --}}
    <div class="bg-white px-6 py-4 flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-black">Form Anak Terpisah</h1>

        <a href="{{ route('admin.anak_terpisah.index') }}"
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

    <form action="{{ route('admin.anak_terpisah.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="bg-white p-6 md:p-10">

            {{-- Atas --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">

                {{-- Foto --}}
                <div class="md:col-span-3 flex flex-col items-center">

                    <div class="w-44 h-44 border-[6px] border-black flex items-center justify-center overflow-hidden bg-white">
                        <img id="preview-foto"
                             class="hidden w-full h-full object-cover">

                        <div id="placeholder-foto" class="text-center">
                            📷
                        </div>
                    </div>

                    <label class="mt-4 cursor-pointer bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg">
                        Upload Foto
                        <input type="file" name="foto_anak" id="foto_anak"
                               class="hidden" accept=".jpg,.jpeg,.png" required>
                    </label>
                </div>

                {{-- Nama --}}
                <div class="md:col-span-9 space-y-4 max-w-xl">

                    <div>
                        <label class="block font-semibold mb-2">Nama Anak</label>
                        <input type="text" name="nama_anak"
                               value="{{ old('nama_anak') }}"
                               class="w-full border rounded-xl px-4 py-3"
                               required>
                    </div>

                    <div>
                        <label class="block font-semibold mb-2">Nama Orangtua / Wali</label>
                        <input type="text" name="nama_ortu_wali"
                               value="{{ old('nama_ortu_wali') }}"
                               class="w-full border rounded-xl px-4 py-3">
                    </div>

                </div>
            </div>

            <div class="border-t my-8"></div>

            {{-- Bawah --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                {{-- Kiri --}}
                <div class="space-y-5">

                    <div>
                        <label class="font-semibold">Jenis Kelamin</label>
                        <div class="flex gap-6 mt-2">
                            <label><input type="radio" name="jenis_kelamin" value="L"> Laki-laki</label>
                            <label><input type="radio" name="jenis_kelamin" value="P"> Perempuan</label>
                        </div>
                    </div>

                    <div>
                        <label class="font-semibold">Umur</label>
                        <input type="number" name="umur"
                               value="{{ old('umur') }}"
                               class="w-full border rounded-xl px-4 py-3">
                    </div>

                    <div>
                        <label class="font-semibold">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir"
                               class="w-full border rounded-xl px-4 py-3">
                    </div>

                    <div>
                        <label class="font-semibold">Tanggal Ditemukan</label>
                        <input type="date" name="tanggal_ditemukan"
                               class="w-full border rounded-xl px-4 py-3"
                               required>
                    </div>

                </div>

                {{-- Kanan --}}
                <div class="space-y-5">

                    <div>
                        <label class="font-semibold">Lokasi Ditemukan</label>
                        <textarea name="lokasi_ditemukan"
                                  class="w-full border rounded-xl px-4 py-3"
                                  required></textarea>
                    </div>

                    <div>
                        <label class="font-semibold">Alamat Asal</label>
                        <textarea name="alamat_asal"
                                  class="w-full border rounded-xl px-4 py-3"></textarea>
                    </div>

                    <div>
                        <label class="font-semibold">Kontak Keluarga</label>
                        <input type="text" name="kontak_keluarga"
                               class="w-full border rounded-xl px-4 py-3">
                    </div>

                    <div>
                        <label class="font-semibold">Status Anak</label>
                        <select name="status_anak"
                                class="w-full border rounded-xl px-4 py-3">
                            <option value="belum_dijemput">Belum Dijemput</option>
                            <option value="dalam_proses">Dalam Proses</option>
                            <option value="sudah_dijemput">Sudah Dijemput</option>
                        </select>
                    </div>

                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end mt-10">
                <button class="bg-green-500 hover:bg-green-600 text-white px-10 py-3 rounded-lg">
                    Simpan
                </button>
            </div>

        </div>
    </form>
</div>

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