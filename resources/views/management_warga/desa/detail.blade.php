@extends('layouts.app')

@section('title', 'Detail Data Desa')

@section('content')
@php
    $userRole = auth()->user()->roles->first()->name ?? 'admin';
    $canManage = !in_array($userRole, ['kabid', 'pegawai']);
@endphp
<div class="space-y-6">

    <div class="text-sm text-gray-500">
        Dashboard <span class="mx-1">&gt;</span> Data Desa <span class="mx-1">&gt;</span> Detail Data Desa
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

        <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 lg:flex-row lg:items-start lg:justify-between">

            <div>
                <h2 class="text-2xl font-bold text-gray-900">Detail Data Desa</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Informasi lengkap desa untuk kebutuhan pendataan wilayah terdampak bencana.
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route($userRole . '.desa.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    Kembali
                </a>

                @if ($canManage)
                    <a href="{{ route($userRole . '.desa.edit', $desa) }}"
                        class="inline-flex items-center justify-center rounded-xl bg-orange-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-orange-600">
                        Edit Data
                    </a>
                @endif
            </div>
        </div>

        <div class="space-y-6 px-6 py-6">

            <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

                <div class="rounded-2xl border border-gray-200 bg-white p-5 lg:col-span-2">

                    <div class="mb-5 flex items-start gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-2xl">🏘️</div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Desa {{ $desa->nama_desa }}</h3>
                            <p class="mt-1 text-sm text-gray-500">Kecamatan {{ $desa->kecamatan }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <p class="text-xs font-medium text-gray-400">ID Desa</p>
                            <p class="mt-2 text-base font-semibold text-gray-900">{{ $desa->id }}</p>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <p class="text-xs font-medium text-gray-400">Kecamatan</p>
                            <p class="mt-2 text-base font-semibold text-gray-900">{{ $desa->kecamatan }}</p>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <p class="text-xs font-medium text-gray-400">Kepala Desa</p>
                            <p class="mt-2 text-base font-semibold text-gray-900">{{ $desa->nama_kades }}</p>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <p class="text-xs font-medium text-gray-400">Kontak</p>
                            <p class="mt-2 text-base font-semibold text-gray-900">{{ $desa->kontak_kades }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-5">
                    <h3 class="text-base font-semibold text-gray-900">Status Data</h3>
                    <div class="mt-5">
                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700">
                            Data Desa Aktif
                        </span>
                    </div>
                </div>

            </div>

            <div>
                <h3 class="mb-4 text-lg font-bold text-gray-900">Informasi Lengkap Desa</h3>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-gray-200 bg-white p-4">
                        <p class="text-xs font-medium text-gray-400">ID Desa</p>
                        <p class="mt-2 text-base font-semibold text-gray-900">{{ $desa->id }}</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-white p-4">
                        <p class="text-xs font-medium text-gray-400">Nama Desa</p>
                        <p class="mt-2 text-base font-semibold text-gray-900">{{ $desa->nama_desa }}</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-white p-4">
                        <p class="text-xs font-medium text-gray-400">Kecamatan</p>
                        <p class="mt-2 text-base font-semibold text-gray-900">{{ $desa->kecamatan }}</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-white p-4">
                        <p class="text-xs font-medium text-gray-400">Nama Kepala Desa</p>
                        <p class="mt-2 text-base font-semibold text-gray-900">{{ $desa->nama_kades }}</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-white p-4 md:col-span-2">
                        <p class="text-xs font-medium text-gray-400">Nomor Handphone Kepala Desa</p>
                        <p class="mt-2 text-base font-semibold text-gray-900">{{ $desa->kontak_kades }}</p>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection