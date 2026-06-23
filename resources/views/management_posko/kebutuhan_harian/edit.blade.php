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
        <form action="{{ route('management_posko.kebutuhan_harian.update', ['role' => $userRole, 'dapur' => $kebutuhan->dapur_umum_id, 'id' => $kebutuhan->id]) }}" method="POST"
            class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-medium mb-2">Tanggal *</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', $kebutuhan->tanggal) }}"
                    class="w-full border rounded-lg p-3">
                @error('tanggal')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror
            </div>

            <div>
                <label class="block font-medium mb-2">Dapur *</label>
                <select name="dapur_umum_id" class="w-full border rounded-lg p-3 bg-gray-100" readonly>
                    @foreach ($dapur as $d)
                        @if($kebutuhan->dapur_umum_id == $d->id)
                            <option value="{{ $d->id }}" selected>
                                {{ $d->nama_dapur_umum }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium mb-2">Jumlah Warga *</label>
                    <input type="number" name="jumlah_warga" value="{{ old('jumlah_warga', $kebutuhan->jumlah_warga) }}"
                        class="w-full border rounded-lg p-3 bg-gray-100" placeholder="Masukkan jumlah warga" readonly>
                    @error('jumlah_warga')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium mb-2">Porsi per Orang *</label>
                    <input type="number" name="porsi_per_orang"
                        value="{{ old('porsi_per_orang', $kebutuhan->porsi_per_orang) }}"
                        class="w-full border rounded-lg p-3" placeholder="Masukkan porsi per orang">
                    @error('porsi_per_orang')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block font-medium mb-2">Total Porsi (Otomatis)</label>
                <input type="number" name="total_porsi" value="{{ old('total_porsi', $kebutuhan->total_porsi) }}"
                    class="w-full border rounded-lg p-3 bg-gray-100" readonly>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('management_posko.kebutuhan_harian.index', ['role' => $userRole, 'dapur' => $kebutuhan->dapur_umum_id]) }}"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg transition">
                    Batal
                </a>

                <button type="submit" class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition">
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

        warga.addEventListener('input', hitungTotal);
        porsi.addEventListener('input', hitungTotal);

        // hitung saat halaman pertama kali load
        document.addEventListener('DOMContentLoaded', hitungTotal);
    </script>
@endsection