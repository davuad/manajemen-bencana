@extends('layouts.app')

@php
    // Mendefinisikan role user dinamis agar tidak error undefined variable
    $userRole = auth()->user()->roles->first()->name ?? 'admin';
@endphp

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold">Edit Data Kebutuhan Harian</h2>
        <p class="text-gray-500 text-sm">
            Perbarui data kebutuhan harian untuk memastikan perhitungan konsumsi tetap akurat
        </p>
    </div>

    <div class="bg-white rounded-xl p-5 m-3 mt-5 shadow-sm">
        <form action="{{ route('management_posko.kebutuhan_harian.update', [
            'role' => $userRole,
            'dapur' => $kebutuhan->dapur_umum_id,
            'id' => $kebutuhan->id,
        ]) }}"
            method="POST" class="space-y-6">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- TANGGAL --}}
                <div>
                    <label class="block font-medium mb-2">
                        Tanggal *
                    </label>

                    <input
                        type="date"
                        name="tanggal"
                        value="{{ old('tanggal', $kebutuhan->tanggal) }}"
                        class="w-full border rounded-lg p-3">

                    @error('tanggal')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                {{-- DAPUR UMUM --}}
                <div>
                    <label class="block font-medium mb-2">
                        Dapur Umum
                    </label>

                    <input
                        type="text"
                        value="{{ $kebutuhan->dapur_umum->nama_dapur_umum }}"
                        class="w-full border rounded-lg p-3 bg-gray-100"
                        readonly>
                </div>

                {{-- JUMLAH WARGA --}}
                <div>
                    <label class="block font-medium mb-2">
                        Jumlah Warga *
                    </label>

                    <input
                        type="number"
                        name="jumlah_warga"
                        value="{{ old('jumlah_warga', $kebutuhan->jumlah_warga) }}"
                        class="w-full border rounded-lg p-3 bg-gray-100"
                        readonly>

                    @error('jumlah_warga')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                {{-- PORSI PER ORANG --}}
                <div>
                    <label class="block font-medium mb-2">
                        Porsi per Orang *
                    </label>

                    <input
                        type="number"
                        name="porsi_per_orang"
                        value="{{ old('porsi_per_orang', $kebutuhan->porsi_per_orang) }}"
                        class="w-full border rounded-lg p-3"
                        placeholder="Masukkan porsi per orang">

                    @error('porsi_per_orang')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                {{-- TOTAL PORSI --}}
                <div>
                    <label class="block font-medium mb-2">
                        Total Porsi (Otomatis)
                    </label>

                    <input
                        type="number"
                        name="total_porsi"
                        value="{{ old('total_porsi', $kebutuhan->total_porsi) }}"
                        class="w-full border rounded-lg p-3 bg-gray-100"
                        readonly>
                </div>

                {{-- REALISASI PORSI --}}
                <div>
                    <label class="block font-medium mb-2">
                        Realisasi Porsi
                        <span class="text-gray-500 text-sm">(Opsional)</span>
                    </label>

                    <input
                        type="number"
                        name="realisasi_porsi"
                        min="0"
                        value="{{ old('realisasi_porsi', $kebutuhan->realisasi_porsi) }}"
                        class="w-full border rounded-lg p-3"
                        placeholder="Masukkan jumlah porsi yang terealisasi">

                    <p class="text-xs text-gray-500 mt-1">
                        Isi jika distribusi makanan telah dilakukan.
                    </p>

                    @error('realisasi_porsi')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                {{-- CATATAN --}}
                <div class="md:col-span-2">
                    <label class="block font-medium mb-2">
                        Catatan
                        <span class="text-gray-500 text-sm">(Opsional)</span>
                    </label>

                    <textarea
                        name="catatan"
                        rows="4"
                        class="w-full border rounded-lg p-3"
                        placeholder="Masukkan catatan jika ada...">{{ old('catatan', $kebutuhan->catatan) }}</textarea>

                    <p class="text-xs text-gray-500 mt-1">
                        Misalnya kendala distribusi, kekurangan bahan, atau informasi lainnya.
                    </p>

                    @error('catatan')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('management_posko.kebutuhan_harian.index', [
                    'role' => $userRole,
                    'dapur' => $kebutuhan->dapur_umum_id,
                ]) }}"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg transition">
                    Batal
                </a>

                <button
                    type="submit"
                    class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition">
                    Update Data
                </button>
            </div>

        </form>
    </div>

    <script>
        const warga = document.querySelector('[name="jumlah_warga"]');
        const porsi = document.querySelector('[name="porsi_per_orang"]');
        const total = document.querySelector('[name="total_porsi"]');

        function hitungTotal() {
            const jumlah = parseInt(warga.value) || 0;
            const perOrang = parseInt(porsi.value) || 0;

            total.value = jumlah * perOrang;
        }

        porsi.addEventListener('input', hitungTotal);

        document.addEventListener('DOMContentLoaded', hitungTotal);
    </script>
@endsection