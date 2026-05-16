@extends('layouts.app')

@section('content')

<div class="mx-3">

    <h2 class="text-2xl font-bold text-gray-800">
        Tambah Kebutuhan Harian
    </h2>

    <p class="text-gray-500 text-sm mt-1">
        Lengkapi data kebutuhan konsumsi untuk
        <span class="font-semibold">
            {{ $dapur->nama_dapur_umum }}
        </span>
    </p>

</div>


<div class="bg-white rounded-xl p-5 m-3 mt-5 shadow-sm">

    <form action="{{ route('management_posko.kebutuhan_harian.store', $dapur->id) }}"
          method="POST"
          class="space-y-6">

        @csrf

        {{-- HIDDEN DAPUR ID --}}
        <input type="hidden"
               name="dapur_umum_id"
               value="{{ $dapur->id }}">


        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- DAPUR UMUM --}}
            <div>

                <label class="block font-medium mb-2">
                    Dapur Umum
                </label>

                <input type="text"
                       value="{{ $dapur->nama_dapur_umum }}"
                       class="w-full border rounded-lg p-3 bg-gray-100"
                       readonly>

            </div>


            {{-- TANGGAL --}}
            <div>

                <label class="block font-medium mb-2">
                    Tanggal *
                </label>

                <input type="date"
                       name="tanggal"
                       value="{{ old('tanggal') }}"
                       class="w-full border rounded-lg p-3">

                @error('tanggal')
                    <small class="text-red-500">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            {{-- JUMLAH WARGA --}}
            <div>

                <label class="block font-medium mb-2">
                    Jumlah Warga *
                </label>

                <input type="number"
                       name="jumlah_warga"
                       value="{{ $dapur->jumlah_warga }}"
                       class="w-full border rounded-lg p-3"
                       placeholder="Masukkan jumlah warga"
                       readonly>

                @error('jumlah_warga')
                    <small class="text-red-500">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            {{-- PORSI PER ORANG --}}
            <div>

                <label class="block font-medium mb-2">
                    Porsi per Orang *
                </label>

                <input type="number"
                       name="porsi_per_orang"
                       value="{{ old('porsi_per_orang') }}"
                       class="w-full border rounded-lg p-3"
                       placeholder="Contoh: 3 kali makan">

                @error('porsi_per_orang')
                    <small class="text-red-500">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            {{-- TOTAL PORSI --}}
            <div class="md:col-span-2">

                <label class="block font-medium mb-2">
                    Total Porsi (Otomatis)
                </label>

                <input type="number"
                       name="total_porsi"
                       value="{{ old('total_porsi') }}"
                       class="w-full border rounded-lg p-3 bg-gray-100"
                       readonly>

            </div>

        </div>


        {{-- BUTTON --}}
        <div class="flex justify-end gap-3">

            {{-- BATAL --}}
            <a href="{{ route('management_posko.kebutuhan_harian.index', $dapur->id) }}"
               class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg">

                Batal

            </a>

            {{-- SUBMIT --}}
            <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">

                Simpan Data

            </button>

        </div>

    </form>

</div>


{{-- SCRIPT AUTO HITUNG --}}
<script>

const warga = document.querySelector('[name="jumlah_warga"]');

const porsi = document.querySelector('[name="porsi_per_orang"]');

const total = document.querySelector('[name="total_porsi"]');


function hitungTotal()
{
    const jumlah = parseInt(warga.value) || 0;

    const perOrang = parseInt(porsi.value) || 0;

    total.value = jumlah * perOrang;
}


warga.addEventListener('input', hitungTotal);

porsi.addEventListener('input', hitungTotal);


document.addEventListener('DOMContentLoaded', hitungTotal);

</script>

@endsection