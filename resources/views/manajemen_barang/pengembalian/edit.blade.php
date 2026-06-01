@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6 max-w-5xl mx-auto">

    {{-- HEADER --}}
    <div class="mb-6">

        <h2 class="text-2xl font-bold">
            Edit Data Pengembalian
        </h2>

        <p class="text-gray-500 text-sm">
            Perbarui data pengembalian barang
        </p>

    </div>

    <form action="{{ route('manajemen_barang.pengembalian.update', $data->id) }}"
          method="POST">

        @csrf
        @method('PUT')

        {{-- PENGAMBILAN --}}
        <div class="mb-6">

            <label class="font-semibold text-gray-700">
                Pengambilan
            </label>

            <input type="text"
                   class="w-full border rounded-lg p-3 mt-2 bg-gray-100"
                   value="{{ $data->pengambilan->bencana->nama_bencana ?? 'Bencana '.$data->pengambilan->bencana_id }} | {{ $data->pengambilan->tujuan }} | {{ $data->pengambilan->tanggal_pengambilan }}"
                   readonly>

        </div>

        {{-- INFO --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

            {{-- BENCANA --}}
            <div>

                <label class="font-semibold text-gray-700">
                    Bencana
                </label>

                <div class="mt-1 text-gray-600">

                    {{ optional($data->pengambilan->bencana)->nama_bencana ?? 'Bencana '.$data->pengambilan->bencana_id }}

                </div>

            </div>

            {{-- TUJUAN --}}
            <div>

                <label class="font-semibold text-gray-700">
                    Tujuan
                </label>

                <div class="mt-1 text-gray-600">

                    {{ $data->pengambilan->tujuan ?? '-' }}

                </div>

            </div>

            {{-- PETUGAS --}}
            <div>

                <label class="font-semibold text-gray-700">
                    Petugas
                </label>

                <div class="mt-1 text-gray-600">

                    {{ $data->petugas->nama_petugas ?? '-' }}

                </div>

            </div>

            {{-- POSKO --}}
            <div>

                <label class="font-semibold text-gray-700">
                    Posko
                </label>

                <div class="mt-1 text-gray-600">

                    {{ $data->posko->nama_posko ?? '-' }}

                </div>

            </div>

        </div>

        {{-- TABLE --}}
        <div class="mb-6">

            <h3 class="text-lg font-semibold mb-4">
                Data Barang Pengembalian
            </h3>

            <div class="overflow-x-auto">

                <table class="w-full border text-sm">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="p-3 text-center">
                                No
                            </th>

                            <th class="p-3 text-left">
                                Nama Barang
                            </th>

                            <th class="p-3 text-center">
                                Satuan
                            </th>

                            <th class="p-3 text-center">
                                Jumlah Diambil
                            </th>

                            <th class="p-3 text-center">
                                Jumlah Dikembalikan
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @php
                            $groupPengambilan = \App\Models\Pengambilan::with('barang')
                                ->where('tujuan', $data->pengambilan->tujuan)
                                ->where('tanggal_pengambilan', $data->pengambilan->tanggal_pengambilan)
                                ->where('posko_id', $data->pengambilan->posko_id)
                                ->get();
                        @endphp

                        @foreach($groupPengambilan as $item)

                        @php
                            $detailKembali = \App\Models\Pengembalian::where(
                                'pengambilan_id',
                                $item->id
                            )->first();
                        @endphp

                        <tr class="border-t">

                            {{-- NO --}}
                            <td class="p-3 text-center">

                                {{ $loop->iteration }}

                            </td>

                            {{-- BARANG --}}
                            <td class="p-3">

                                {{ $item->barang->nama_barang ?? '-' }}

                                <input type="hidden"
                                       name="pengambilan_id[]"
                                       value="{{ $item->id }}">

                            </td>

                            {{-- SATUAN --}}
                            <td class="p-3 text-center">

                                {{ $item->barang->satuan ?? '-' }}

                            </td>

                            {{-- JUMLAH AMBIL --}}
                            <td class="p-3 text-center">

                                <span class="px-3 py-1 rounded-lg bg-blue-100 text-blue-700 font-semibold">

                                    {{ $item->jumlah_ambil }}

                                </span>

                            </td>

                            {{-- JUMLAH KEMBALI --}}
                            <td class="p-3 text-center">

                                <input type="number"
                                       name="jumlah_kembali[]"
                                       min="0"
                                       max="{{ $item->jumlah_ambil }}"
                                       value="{{ $detailKembali->jumlah_kembali ?? 0 }}"
                                       class="border rounded-lg p-2 w-24 text-center">

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        {{-- FORM --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- TANGGAL --}}
            <div>

                <label class="font-semibold text-gray-700">
                    Tanggal Pengembalian
                </label>

                <input type="date"
                       name="tanggal_pengembalian"
                       value="{{ old('tanggal_pengembalian', $data->tanggal_pengembalian) }}"
                       class="w-full border rounded-lg p-3 mt-2"
                       required>

            </div>

            {{-- STATUS --}}
            <div>

                <label class="font-semibold text-gray-700">
                    Status
                </label>

                <select name="status"
                        class="w-full border rounded-lg p-3 mt-2"
                        required>

                    <option value="Ditangani"
                        {{ $data->status == 'Ditangani' ? 'selected' : '' }}>
                        Ditangani
                    </option>

                    <option value="Selesai"
                        {{ $data->status == 'Selesai' ? 'selected' : '' }}>
                        Selesai
                    </option>

                    <option value="Dibatalkan"
                        {{ $data->status == 'Dibatalkan' ? 'selected' : '' }}>
                        Dibatalkan
                    </option>

                </select>

            </div>

            {{-- KETERANGAN --}}
                <div class="col-span-1 md:col-span-2">

                    <label class="font-semibold text-gray-700">
                        Keterangan
                    </label>

                    <textarea
                        name="keterangan"
                        rows="4"
                        class="w-full border rounded-lg p-3 mt-2 resize-none"
                        placeholder="Opsional">{{ old('keterangan', $data->keterangan) }}</textarea>

                </div>

        </div>

        {{-- BUTTON --}}
        <div class="mt-8 flex justify-between">

            <a href="{{ route('manajemen_barang.pengembalian.index') }}"
               class="px-5 py-2 bg-gray-300 rounded-lg">

                Kembali

            </a>

            <div class="flex gap-3">

                <button type="submit"
                        class="px-6 py-2 bg-yellow-500 text-white rounded-lg">

                    Update

                </button>

                <button type="button"
                        onclick="if(confirm('Yakin ingin menghapus data ini?')) document.getElementById('hapusForm').submit();"
                        class="px-5 py-2 bg-red-500 text-white rounded-lg">

                    Hapus

                </button>

            </div>

        </div>

    </form>

    {{-- FORM DELETE --}}
    <form id="hapusForm"
          action="{{ route('manajemen_barang.pengembalian.destroy', $data->id) }}"
          method="POST"
          class="hidden">

        @csrf
        @method('DELETE')

    </form>

</div>

@endsection