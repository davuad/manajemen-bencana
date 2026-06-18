@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            Edit Penerima Distribusi
        </h2>

        <p class="text-gray-500 mt-1">
            Perbarui data penerima distribusi bantuan.
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">

        <form action="{{ route('admin.management_distribusi.penerima.update', $data->penerima_id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Detail Distribusi --}}
                <div class="md:col-span-2">

                    <label class="block mb-2 font-semibold">

                        Detail Distribusi
                        <span class="text-red-500">*</span>

                    </label>

                    <select
                        id="detail_distribusi"
                        name="detail_distribusi_id"
                        class="w-full border rounded-lg px-4 py-3">

                        <option value="">
                            -- Pilih Detail Distribusi --
                        </option>

                        @foreach($detailDistribusi as $item)

                        <option

                            value="{{ $item->id }}"

                            data-posko="{{ $item->distribusi->posko->nama_posko ?? '-' }}"

                            data-bencana="{{ $item->distribusi->bencana->nama_bencana ?? '-' }}"

                            data-lokasi="{{ $item->distribusi->lokasi_distribusi ?? '-' }}"

                            data-tanggal="{{ $item->distribusi->tanggal_distribusi ?? '-' }}"

                            {{ old('detail_distribusi_id',$data->detail_distribusi_id)==$item->id ? 'selected' : '' }}>

                            {{ $item->distribusi->tanggal_distribusi }}
                            |
                            {{ $item->distribusi->posko->nama_posko ?? '-' }}
                            |
                            {{ $item->distribusi->bencana->nama_bencana ?? '-' }}

                        </option>

                        @endforeach

                    </select>

                    @error('detail_distribusi_id')

                    <small class="text-red-500">

                        {{ $message }}

                    </small>

                    @enderror

                </div>


                {{-- Informasi Distribusi --}}
                <div class="md:col-span-2">

                    <div
                        class="bg-blue-50 border border-blue-200 rounded-xl p-5">

                        <h3
                            class="font-semibold text-blue-700 mb-4">

                            Informasi Distribusi

                        </h3>

                        <div class="grid md:grid-cols-2 gap-4">

                            <div>

                                <label class="text-sm text-gray-500">

                                    Posko

                                </label>

                                <p
                                    id="infoPosko"
                                    class="font-semibold">

                                    -

                                </p>

                            </div>

                            <div>

                                <label class="text-sm text-gray-500">

                                    Bencana

                                </label>

                                <p
                                    id="infoBencana"
                                    class="font-semibold">

                                    -

                                </p>

                            </div>

                            <div>

                                <label class="text-sm text-gray-500">

                                    Lokasi Distribusi

                                </label>

                                <p
                                    id="infoLokasi"
                                    class="font-semibold">

                                    -

                                </p>

                            </div>

                            <div>

                                <label class="text-sm text-gray-500">

                                    Tanggal Distribusi

                                </label>

                                <p
                                    id="infoTanggal"
                                    class="font-semibold">

                                    -

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- Nama --}}
                <div>

                    <label class="block mb-2 font-semibold">

                        Nama Penerima

                    </label>

                    <input
                        type="text"
                        name="nama_penerima"
                        value="{{ old('nama_penerima',$data->nama_penerima) }}"
                        class="w-full border rounded-lg px-4 py-3">

                </div>

                {{-- Jabatan --}}
                <div>

                    <label class="block mb-2 font-semibold">

                        Jabatan

                    </label>

                    <input
                        type="text"
                        name="jabatan"
                        value="{{ old('jabatan',$data->jabatan) }}"
                        class="w-full border rounded-lg px-4 py-3">

                </div>

                {{-- Instansi --}}
                <div>

                    <label class="block mb-2 font-semibold">

                        Instansi

                    </label>

                    <input
                        type="text"
                        name="instansi"
                        value="{{ old('instansi',$data->instansi) }}"
                        class="w-full border rounded-lg px-4 py-3">

                </div>

                {{-- No HP --}}
                <div>

                    <label class="block mb-2 font-semibold">

                        Nomor HP

                    </label>

                    <input
                        type="text"
                        name="no_hp"
                        value="{{ old('no_hp',$data->no_hp) }}"
                        class="w-full border rounded-lg px-4 py-3">

                </div>

                {{-- Alamat --}}
                <div class="md:col-span-2">

                    <label class="block mb-2 font-semibold">

                        Alamat

                    </label>

                    <textarea
                        name="alamat"
                        rows="3"
                        class="w-full border rounded-lg px-4 py-3">{{ old('alamat',$data->alamat) }}</textarea>

                </div>

                {{-- Status --}}
                <div class="md:col-span-2">

                    <label class="block mb-3 font-semibold">

                        Status

                    </label>

                    <div class="flex gap-8">

                        <label class="flex items-center gap-2">

                            <input
                                type="radio"
                                name="status"
                                value="Aktif"

                                {{ old('status',$data->status)=='Aktif' ? 'checked' : '' }}>

                            <span
                                class="text-green-600 font-medium">

                                Aktif

                            </span>

                        </label>

                        <label class="flex items-center gap-2">

                            <input
                                type="radio"
                                name="status"
                                value="Tidak Aktif"

                                {{ old('status',$data->status)=='Tidak Aktif' ? 'checked' : '' }}>

                            <span
                                class="text-red-600 font-medium">

                                Tidak Aktif

                            </span>

                        </label>

                    </div>

                </div>

                <div class="md:col-span-2 flex justify-end gap-3 mt-4">

                    <a
                        href="{{ route('admin.management_distribusi.penerima.index') }}"
                        class="px-5 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">

                        Batal

                    </a>

                    <button
                        type="submit"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg">

                        Update Data

                    </button>

                </div>
            </div>
                    </form>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const detailDistribusi = document.getElementById('detail_distribusi');

    const infoPosko = document.getElementById('infoPosko');
    const infoBencana = document.getElementById('infoBencana');
    const infoLokasi = document.getElementById('infoLokasi');
    const infoTanggal = document.getElementById('infoTanggal');

    function tampilkanInformasi() {

        if (detailDistribusi.selectedIndex < 0) {
            return;
        }

        const selected =
            detailDistribusi.options[detailDistribusi.selectedIndex];

        if(detailDistribusi.value == ''){

            infoPosko.innerHTML = '-';
            infoBencana.innerHTML = '-';
            infoLokasi.innerHTML = '-';
            infoTanggal.innerHTML = '-';

            return;
        }

        infoPosko.innerHTML =
            selected.dataset.posko ?? '-';

        infoBencana.innerHTML =
            selected.dataset.bencana ?? '-';

        infoLokasi.innerHTML =
            selected.dataset.lokasi ?? '-';

        infoTanggal.innerHTML =
            selected.dataset.tanggal ?? '-';

    }

    detailDistribusi.addEventListener('change', function(){

        tampilkanInformasi();

    });

    // Jalankan saat pertama kali halaman dibuka
    tampilkanInformasi();

});
</script>

@endsection