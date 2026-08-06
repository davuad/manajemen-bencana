@extends('layouts.app')

@section('title', 'Edit Data Desa')

@section('content')
@php
    $userRole = auth()->user()->roles->first()->name ?? 'admin';
@endphp
<div class="space-y-6">

    <div class="text-sm text-gray-500">
        Dashboard <span class="mx-1">&gt;</span> Data Desa <span class="mx-1">&gt;</span> Edit Data Desa
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

        <div class="border-b border-gray-100 px-6 py-5">
            <h2 class="text-xl font-bold text-gray-900">Edit Data Desa</h2>
        </div>

        <div class="px-6 py-6 md:px-8 md:py-8">

            <form action="{{ route($userRole . '.desa.update', $desa->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 md:gap-x-10 md:gap-y-7">

                    <div>
                        <label for="nama_desa" class="mb-2 block text-sm font-medium text-gray-700">Nama Desa</label>
                        <input type="text" id="nama_desa" name="nama_desa" value="{{ old('nama_desa', $desa->nama_desa) }}"
                            placeholder="Masukkan nama desa"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100">
                        @error('nama_desa')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="nama_kades" class="mb-2 block text-sm font-medium text-gray-700">Nama Kepala Desa</label>
                        <input type="text" id="nama_kades" name="nama_kades" value="{{ old('nama_kades', $desa->nama_kades) }}"
                            placeholder="Masukkan nama kepala desa"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100">
                        @error('nama_kades')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="kecamatan" class="mb-2 block text-sm font-medium text-gray-700">Kecamatan</label>
                        <input type="text" id="kecamatan" name="kecamatan" value="{{ old('kecamatan', $desa->kecamatan) }}"
                            placeholder="Masukkan kecamatan"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100">
                        @error('kecamatan')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="kontak_kades" class="mb-2 block text-sm font-medium text-gray-700">Nomor Handphone Kepala Desa</label>
                        <input type="text" id="kontak_kades" name="kontak_kades" value="{{ old('kontak_kades', $desa->kontak_kades) }}"
                            placeholder="Masukkan nomor handphone kepala desa"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100">
                        @error('kontak_kades')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:justify-end">
                    <a href="{{ route($userRole . '.desa.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-indigo-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-800">
                        Update
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection