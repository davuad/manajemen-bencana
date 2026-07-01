<!-- @extends('layouts.app')

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold">Tambah Data Bencana</h2>
    </div>

    <div class="bg-white rounded-xl p-5 m-3 mt-5">

        <form action="{{ route('admin.bencana.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label>Nama Bencana *</label>
                    <input type="text" name="nama_bencana" class="w-full border p-3 rounded-lg">
                </div>

                <div>
                    <label>Kategori *</label>
                    <select name="kategori_id" class="w-full border p-3 rounded-lg">
                        @foreach ($kategori as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Desa</label>
                    <select name="desa_id" class="w-full border p-3 rounded-lg">
                        <option value="">-- Pilih Desa --</option>
                        @foreach ($desa as $d)
                            <option value="{{ $d->id }}">{{ $d->nama_desa }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Pengaduan</label>
                    <select name="pengaduan_id" class="w-full border p-3 rounded-lg">
                        <option value="">-- Pilih Pengaduan --</option>
                        @foreach ($pengaduan as $p)
                            <option value="{{ $p->id }}">{{ $p->deskripsi }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Tanggal *</label>
                    <input type="date" name="tanggal" class="w-full border p-3 rounded-lg">
                </div>

                <div>
                    <label>Status *</label>
                    <select name="status_bencana" class="w-full border p-3 rounded-lg">
                        <option value="berlangsung">Berlangsung</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>

                <div>
                    <label>Kerusakan *</label>
                    <select name="tingkat_kerusakan" class="w-full border p-3 rounded-lg">
                        <option value="ringan">Ringan</option>
                        <option value="sedang">Sedang</option>
                        <option value="parah">Parah</option>
                    </select>
                </div>

            </div>

            <div class="flex justify-end mt-6 gap-3">
                <a href="{{ route('admin.bencana.index') }}" class="bg-gray-300 px-4 py-2 rounded">Batal</a>
                <button class="bg-blue-600 text-white px-6 py-2 rounded">Simpan</button>
            </div>

        </form>

    </div>
@endsection -->

@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

```
{{-- HEADER --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        Tambah Data Bencana
    </h1>
    <p class="text-gray-500 mt-1">
        Masukkan informasi kejadian bencana secara lengkap.
    </p>
</div>

{{-- VALIDATION --}}
@if ($errors->any())
    <div class="mb-5 rounded-lg border border-red-300 bg-red-50 p-4">
        <h3 class="font-semibold text-red-700 mb-2">
            Terjadi kesalahan:
        </h3>

        <ul class="list-disc ml-5 text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white shadow rounded-2xl p-6">

    <form action="{{ route('admin.bencana.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- NAMA --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Bencana <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    name="nama_bencana"
                    value="{{ old('nama_bencana') }}"
                    placeholder="Contoh: Banjir Sungai Serayu"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            {{-- KATEGORI --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Kategori Bencana <span class="text-red-500">*</span>
                </label>

                <select
                    name="kategori_id"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500">

                    @foreach ($kategori as $k)
                        <option value="{{ $k->id }}">
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- DESA --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Desa
                </label>

                <select
                    name="desa_id"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3">

                    <option value="">
                        -- Pilih Desa --
                    </option>

                    @foreach ($desa as $d)
                        <option value="{{ $d->id }}">
                            {{ $d->nama_desa }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- PENGADUAN --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Pengaduan
                </label>

                <select
                    name="pengaduan_id"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3">

                    <option value="">
                        -- Pilih Pengaduan --
                    </option>

                    @foreach ($pengaduan as $p)
                        <option value="{{ $p->id }}">
                            {{ Str::limit($p->deskripsi, 50) }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- TANGGAL --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tanggal Kejadian <span class="text-red-500">*</span>
                </label>

                <input
                    type="date"
                    name="tanggal"
                    value="{{ old('tanggal') }}"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3">
            </div>

            {{-- STATUS --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Status Bencana <span class="text-red-500">*</span>
                </label>

                <select
                    name="status_bencana"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3">

                    <option value="berlangsung">
                        🟠 Berlangsung
                    </option>

                    <option value="selesai">
                        🟢 Selesai
                    </option>

                </select>
            </div>

            {{-- KERUSAKAN --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tingkat Kerusakan <span class="text-red-500">*</span>
                </label>

                <select
                    name="tingkat_kerusakan"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3">

                    <option value="ringan">🟢 Ringan</option>
                    <option value="sedang">🟡 Sedang</option>
                    <option value="parah">🔴 Parah</option>

                </select>
            </div>

        </div>

        <div class="flex justify-end gap-3 mt-8">

            <a href="{{ route('admin.bencana.index') }}"
               class="px-5 py-3 rounded-xl bg-gray-200 hover:bg-gray-300 transition">
                Batal
            </a>

            <button
                type="submit"
                class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-medium transition">

                Simpan Data

            </button>

        </div>

    </form>

</div>
```

</div>

@endsection
