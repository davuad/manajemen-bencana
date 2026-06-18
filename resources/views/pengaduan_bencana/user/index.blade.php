@extends('layouts.app')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

{{-- Header --}}
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">

    <div>
        <h2 class="text-2xl font-bold text-gray-800">
            Pengaduan Saya
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Daftar pengaduan bencana yang telah Anda laporkan.
        </p>
    </div>

    <a href="{{ route('user.pengaduan.create') }}"
        class="bg-indigo-700 hover:bg-indigo-800 text-white px-5 py-2.5 rounded-xl text-sm font-medium shadow-sm transition duration-200">

        + Buat Pengaduan

    </a>

</div>

{{-- Alert --}}
@if(session('success'))

    <div class="mb-6 rounded-xl bg-green-100 border border-green-300 text-green-700 px-4 py-3">

        {{ session('success') }}

    </div>

@endif

{{-- Filter --}}
<form method="GET"
      action="{{ route('user.pengaduan.index') }}"
      class="mb-6">

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

        <div class="md:col-span-5">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari desa atau deskripsi..."
                class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

        </div>

        <div class="md:col-span-4">

            <select
                name="status"
                class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                <option value="">Semua Status</option>

                <option value="BELUM_DITANGANI"
                    {{ request('status')=='BELUM_DITANGANI' ? 'selected' : '' }}>
                    Belum Ditangani
                </option>

                <option value="DITANGANI"
                    {{ request('status')=='DITANGANI' ? 'selected' : '' }}>
                    Ditangani
                </option>

                <option value="SELESAI"
                    {{ request('status')=='SELESAI' ? 'selected' : '' }}>
                    Selesai
                </option>

            </select>

        </div>

        <div class="md:col-span-3">

            <button
                type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white rounded-xl px-4 py-2.5 transition">

                Cari

            </button>

        </div>

    </div>

</form>

{{-- Table --}}
<div class="overflow-x-auto rounded-xl border border-gray-200">

    <table class="min-w-full divide-y divide-gray-200">

        <thead class="bg-gray-50">

            <tr>

                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">
                    No
                </th>

                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">
                    Kategori
                </th>

                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">
                    Desa
                </th>

                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600">
                    Deskripsi
                </th>

                <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-600">
                    Status
                </th>

                <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-600">
                    Tanggal
                </th>

                <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-600">
                    Aksi
                </th>

            </tr>

        </thead>

        <tbody class="divide-y divide-gray-100 bg-white">

        @forelse($data as $item)

            <tr class="hover:bg-gray-50">

                <td class="px-4 py-3">

                    {{ $loop->iteration }}

                </td>

                <td class="px-4 py-3">

                    {{ $item->kategori->nama_kategori ?? '-' }}

                </td>

                <td class="px-4 py-3">

                    {{ $item->desa }}

                </td>

                <td class="px-4 py-3">

                    {{ \Illuminate\Support\Str::limit($item->deskripsi, 60) }}

                </td>

                <td class="px-4 py-3 text-center">

                    @if($item->status_pengaduan == 'BELUM_DITANGANI')

                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                            Belum Ditangani
                        </span>

                    @elseif($item->status_pengaduan == 'DITANGANI')

                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                            Ditangani
                        </span>

                    @else

                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            Selesai
                        </span>

                    @endif

                </td>

                <td class="px-4 py-3 text-center">

                    {{ $item->created_at->format('d-m-Y') }}

                </td>

                <td class="px-4 py-3 text-center">

                    <a href="{{ route('user.pengaduan.show',$item->id) }}"
                        class="inline-flex items-center px-3 py-2 rounded-lg bg-sky-500 hover:bg-sky-600 text-white text-sm transition">

                        Detail

                    </a>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="7"
                    class="text-center py-10 text-gray-500">

                    Belum ada data pengaduan.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

</div>

@endsection
