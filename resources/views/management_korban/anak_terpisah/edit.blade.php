@extends('layouts.app')

@section('content')
@php
    $prefix = auth()->user()->hasRole('petugas') ? 'petugas' : 'admin';
@endphp

<div class="bg-slate-200 min-h-screen p-4 md:p-6">

    {{-- Header --}}
    <div class="bg-white px-6 py-4 flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-black">Edit Data Anak Terpisah</h1>
            <p class="text-sm text-gray-500">
                Perbarui data anak yang ditemukan
            </p>
        </div>

        <a href="{{ route($prefix.'.anak_terpisah.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg shadow">
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

    <form action="{{ route($prefix.'.anak_terpisah.update', $anak->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="bg-white p-6 md:p-10 rounded-xl">

            {{-- GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                {{-- KIRI --}}
                <div class="space-y-5">

                    <div>
                        <label class="font-semibold">Bencana</label>

                        <select name="bencana_id"
                                class="w-full border rounded-xl px-4 py-3"
                                required>

                            <option value="">Pilih Bencana</option>

                            @foreach($bencana as $b)
                                <option value="{{ $b->id }}"
                                    {{ old('bencana_id', $anak->bencana_id) == $b->id ? 'selected' : '' }}>
                                    {{ $b->nama_bencana }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div>
                        <label class="font-semibold">Nama Anak</label>
                        <input type="text"
                            name="nama_anak"
                            value="{{ old('nama_anak', $anak->nama_anak) }}"
                            class="w-full border rounded-xl px-4 py-3"
                            required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="font-semibold">Nama Bapak</label>
                            <input type="text"
                                name="nama_bapak"
                                value="{{ old('nama_bapak', $anak->nama_bapak) }}"
                                class="w-full border rounded-xl px-4 py-3">
                        </div>

                        <div>
                            <label class="font-semibold">Nama Ibu</label>
                            <input type="text"
                                name="nama_ibu"
                                value="{{ old('nama_ibu', $anak->nama_ibu) }}"
                                class="w-full border rounded-xl px-4 py-3">
                        </div>

                    </div>

                    <div>
                        <label class="font-semibold">Jenis Kelamin</label>
                        <select name="jenis_kelamin"
                                class="w-full border rounded-xl px-4 py-3">
                            <option value="L" {{ $anak->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ $anak->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="font-semibold">Umur</label>
                        <input type="number" name="umur"
                               value="{{ old('umur', $anak->umur) }}"
                               class="w-full border rounded-xl px-4 py-3">
                    </div>

                    <div>
                        <label class="font-semibold">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir"
                               value="{{ old('tanggal_lahir', $anak->tanggal_lahir) }}"
                               class="w-full border rounded-xl px-4 py-3">
                    </div>

                </div>

                {{-- KANAN --}}
                <div class="space-y-5">

                    <div>
                        <label class="font-semibold">Lokasi Ditemukan</label>
                        <textarea name="lokasi_ditemukan"
                                  class="w-full border rounded-xl px-4 py-3"
                                  required>{{ old('lokasi_ditemukan', $anak->lokasi_ditemukan) }}</textarea>
                    </div>

                    <div>
                        <label class="font-semibold">Tanggal Ditemukan</label>
                        <input type="date" name="tanggal_ditemukan"
                               value="{{ old('tanggal_ditemukan', $anak->tanggal_ditemukan) }}"
                               class="w-full border rounded-xl px-4 py-3"
                               required>
                    </div>

                    <div>
                        <label class="font-semibold">Status Anak</label>
                        <select name="status_anak"
                                class="w-full border rounded-xl px-4 py-3">
                            <option value="belum_dijemput" {{ $anak->status_anak == 'belum_dijemput' ? 'selected' : '' }}>Belum Dijemput</option>
                            <option value="dalam_proses" {{ $anak->status_anak == 'dalam_proses' ? 'selected' : '' }}>Dalam Proses</option>
                            <option value="sudah_dijemput" {{ $anak->status_anak == 'sudah_dijemput' ? 'selected' : '' }}>Sudah Dijemput</option>
                        </select>
                    </div>

                    <div>
                        <label class="font-semibold">Foto Anak</label>

                        @if($anak->foto_anak)
                            <img src="{{ asset('storage/'.$anak->foto_anak) }}"
                                 class="w-32 h-32 object-cover rounded-xl mb-3">
                        @endif

                        <input type="file" name="foto_anak"
                               class="w-full border rounded-xl px-3 py-2">
                    </div>

                </div>
            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end gap-3 mt-10">

                <a href="{{ route($prefix.'.anak_terpisah.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 px-6 py-3 rounded-lg">
                    Batal
                </a>

                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg">
                    Update Data
                </button>

            </div>

        </div>
    </form>
</div>
@endsection