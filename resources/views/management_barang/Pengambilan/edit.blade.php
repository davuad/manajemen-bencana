@extends('layouts.app')

@section('content')

<div class="mx-3">

    <h2 class="text-2xl font-bold">
        Edit Data Pengambilan
    </h2>

    <p class="text-gray-500 text-sm">
        Perbarui data pengambilan barang
    </p>

</div>

<div class="bg-white rounded-xl p-6 m-3 mt-5 shadow">

    {{-- Error --}}
    @if ($errors->any())

        <div class="mb-5 bg-red-100 text-red-700 p-4 rounded-lg">

            <ul class="list-disc pl-5">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    {{-- Form --}}
    <form action="{{ route('manajemen_barang.pengambilan.update', $data->id) }}"
          method="POST"
          class="space-y-8">

        @csrf
        @method('PUT')

        {{-- Informasi --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Bencana --}}
            <div>

                <label class="block font-medium mb-2">
                    Bencana
                </label>

                <select name="bencana_id"
                        class="w-full border rounded-lg p-3">

                    <option value="">
                        -- Pilih Bencana --
                    </option>

                    @foreach($bencana as $b)

                    <option value="{{ $b->id }}"
                        {{ old('bencana_id', $data->bencana_id) == $b->id ? 'selected' : '' }}>

                        {{ $b->nama_bencana ?? 'Bencana '.$b->id }}

                    </option>

                    @endforeach

                </select>

            </div>

            {{-- Petugas --}}
            <div>

                <label class="block font-medium mb-2">
                    Petugas
                </label>

                <select name="petugas_id"
                        class="w-full border rounded-lg p-3">

                    <option value="">
                        -- Pilih Petugas --
                    </option>

                    @foreach($petugas as $pt)

                    <option value="{{ $pt->id }}"
                        {{ old('petugas_id', $data->petugas_id) == $pt->id ? 'selected' : '' }}>

                        {{ $pt->nama_petugas }}

                    </option>

                    @endforeach

                </select>

            </div>

            {{-- Tanggal --}}
            <div>

                <label class="block font-medium mb-2">
                    Tanggal Pengambilan
                </label>

                <input type="date"
                       name="tanggal_pengambilan"
                       value="{{ old('tanggal_pengambilan', $data->tanggal_pengambilan) }}"
                       class="w-full border rounded-lg p-3">

            </div>

            {{-- Posko --}}
            <div>

                <label class="block font-medium mb-2">
                    Posko
                </label>

                <select name="posko_id"
                        class="w-full border rounded-lg p-3">

                    <option value="">
                        -- Pilih Posko --
                    </option>

                    @foreach($posko as $p)

                    <option value="{{ $p->id }}"
                        {{ old('posko_id', $data->posko_id) == $p->id ? 'selected' : '' }}>

                        {{ $p->nama_posko }}

                    </option>

                    @endforeach

                </select>

            </div>

            {{-- Tujuan --}}
            <div class="md:col-span-2">

                <label class="block font-medium mb-2">
                    Tujuan
                </label>

                <textarea name="tujuan"
                          rows="3"
                          class="w-full border rounded-lg p-3">{{ old('tujuan', $data->tujuan) }}</textarea>

            </div>

        </div>

        {{-- Data Barang --}}
        <div>

            <div class="flex justify-between items-center mb-4">

                <h3 class="text-lg font-semibold">
                    Data Barang
                </h3>

                <button type="button"
                        id="tambahRow"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg">

                    + Tambah Barang

                </button>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full border text-sm"
                       id="tableBarang">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="p-3 text-left">
                                Barang
                            </th>

                            <th class="p-3 text-center">
                                Stok
                            </th>

                            <th class="p-3 text-center">
                                Jumlah Ambil
                            </th>

                            <th class="p-3 text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @php
                            $barangPengambilan = \App\Models\Pengambilan::where(
                                'tanggal_pengambilan',
                                $data->tanggal_pengambilan
                            )
                            ->where('petugas_id', $data->petugas_id)
                            ->where('tujuan', $data->tujuan)
                            ->get();
                        @endphp

                        @foreach($barangPengambilan as $item)

                        <tr>

                            {{-- Barang --}}
                            <td class="p-3">

                                <select name="barang_id[]"
                                        class="w-full border rounded-lg p-2 barang-select">

                                    <option value="">
                                        -- Pilih Barang --
                                    </option>

                                    @foreach($barang as $br)

                                    <option value="{{ $br->id }}"
                                            data-stok="{{ $br->stok }}"
                                            {{ $item->barang_id == $br->id ? 'selected' : '' }}>

                                        {{ $br->nama_barang }}

                                    </option>

                                    @endforeach

                                </select>

                            </td>

                            {{-- Stok --}}
                            <td class="p-3 text-center stok-text">

                                {{ $item->barang->stok ?? 0 }}

                            </td>

                            {{-- Jumlah --}}
                            <td class="p-3">

                                <input type="number"
                                       name="jumlah_ambil[]"
                                       value="{{ $item->jumlah_ambil }}"
                                       min="0"
                                       class="w-full border rounded-lg p-2 jumlah-input">

                            </td>

                            {{-- Hapus --}}
                            <td class="p-3 text-center">

                                <button type="button"
                                        class="hapusRow text-red-500 hover:text-red-700">

                                    Hapus

                                </button>

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        {{-- Status --}}
        <div>

            <label class="block font-medium mb-3">
                Status
            </label>

            <div class="flex gap-3 flex-wrap">

                @foreach(['Ditangani','Selesai','Dibatalkan'] as $s)

                <label class="cursor-pointer">

                    <input type="radio"
                           name="status"
                           value="{{ $s }}"
                           {{ old('status', $data->status) == $s ? 'checked' : '' }}
                           class="hidden peer">

                    <span class="px-4 py-2 rounded-full font-semibold
                        bg-gray-100 text-gray-700
                        peer-checked:bg-blue-600 peer-checked:text-white">

                        {{ $s }}

                    </span>

                </label>

                @endforeach

            </div>

        </div>

        {{-- Button --}}
        <div class="flex justify-between">

            <a href="{{ route('manajemen_barang.pengambilan.index') }}"
               class="px-5 py-2 bg-gray-300 rounded-lg">

                Kembali

            </a>

            <button type="submit"
                    class="px-6 py-2 bg-yellow-500 text-white rounded-lg">

                Update

            </button>

        </div>

    </form>

</div>

{{-- SCRIPT --}}
<script>

    // tambah row
    document.getElementById('tambahRow')
        .addEventListener('click', function () {

        let table =
            document.querySelector('#tableBarang tbody');

        let row =
            table.rows[0].cloneNode(true);

        // reset input
        row.querySelectorAll('input')
            .forEach(input => {

                if(input.type !== 'radio') {

                    if(input.name === 'jumlah_ambil[]') {

                        let status =
                            document.querySelector(
                                'input[name="status"]:checked'
                            )?.value;

                        input.value =
                            status === 'Dibatalkan'
                            ? 0
                            : 1;

                    } else {

                        input.value = '';

                    }

                }

            });

        // reset select
        row.querySelectorAll('select')
            .forEach(select => {
                select.selectedIndex = 0;
            });

        // reset stok
        row.querySelector('.stok-text')
            .innerText = '0';

        table.appendChild(row);

        checkStatusBatal();
    });

    // stok otomatis
    document.addEventListener('change', function(e){

        if(e.target.classList.contains('barang-select')) {

            let stok =
                e.target.options[e.target.selectedIndex]
                    .getAttribute('data-stok');

            e.target.closest('tr')
                .querySelector('.stok-text')
                .innerText = stok;
        }
    });

    // hapus row
    document.addEventListener('click', function(e){

        if(e.target.classList.contains('hapusRow')) {

            let rows =
                document.querySelectorAll('#tableBarang tbody tr');

            if(rows.length > 1) {

                e.target.closest('tr').remove();

            }
        }
    });

    // status batal
    function checkStatusBatal() {

        let status =
            document.querySelector(
                'input[name="status"]:checked'
            )?.value;

        let jumlahInputs =
            document.querySelectorAll('.jumlah-input');

        let tombolTambah =
            document.getElementById('tambahRow');

        let tombolHapus =
            document.querySelectorAll('.hapusRow');

        if(status === 'Dibatalkan') {

            jumlahInputs.forEach(input => {

                input.value = 0;

                input.readOnly = true;

                input.classList.add(
                    'bg-gray-100',
                    'cursor-not-allowed'
                );

            });

            tombolTambah.disabled = true;

            tombolTambah.classList.add(
                'opacity-50',
                'cursor-not-allowed'
            );

            tombolHapus.forEach(btn => {

                btn.disabled = true;

                btn.classList.add(
                    'opacity-50',
                    'cursor-not-allowed'
                );

            });

        } else {

            jumlahInputs.forEach(input => {

                if(input.value == 0) {
                    input.value = 1;
                }

                input.readOnly = false;

                input.classList.remove(
                    'bg-gray-100',
                    'cursor-not-allowed'
                );

            });

            tombolTambah.disabled = false;

            tombolTambah.classList.remove(
                'opacity-50',
                'cursor-not-allowed'
            );

            tombolHapus.forEach(btn => {

                btn.disabled = false;

                btn.classList.remove(
                    'opacity-50',
                    'cursor-not-allowed'
                );

            });

        }
    }

    // trigger status
    document.querySelectorAll(
        'input[name="status"]'
    ).forEach(radio => {

        radio.addEventListener(
            'change',
            checkStatusBatal
        );

    });

    // pertama load
    checkStatusBatal();

</script>

@endsection