@extends('layouts.app')

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold">Tambah Data Kebutuhan Harian</h2>
        <p class="text-gray-500 text-sm">
           Lengkapi data kebutuhan konsumsi harian untuk mendukung operasional dapur umum
        </p>
    </div>

    <div class="bg-white rounded-xl p-5 m-3 mt-5">
        <form action="{{ route('management_posko.kebutuhan_harian.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Tanggal -->
                <div>
                    <label>Tanggal *</label>
                    <input type="date" name="tanggal"
                        value="{{ old('tanggal') }}"
                        class="w-full border rounded-lg p-3">
                </div>

                <!-- Dapur -->
                <div>
                    <label>Dapur *</label>
                    <select name="dapur_umum_id" class="w-full border rounded-lg p-3">
                        <option value="">Pilih Dapur</option>
                        @foreach($dapur as $d)
                            <option value="{{ $d->id }}"
                                {{ old('dapur_umum_id') == $d->id ? 'selected' : '' }}>
                                {{ $d->nama_dapur_umum }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Jumlah Warga -->
                <div>
                    <label>Jumlah Warga *</label>
                    <input type="number" name="jumlah_warga"
                        value="{{ old('jumlah_warga') }}"
                        class="w-full border rounded-lg p-3"
                        placeholder="Masukkan jumlah warga">
                </div>

                <!-- Porsi per Orang -->
                <div>
                    <label>Porsi per Orang *</label>
                    <input type="number" name="porsi_per_orang"
                        value="{{ old('porsi_per_orang') }}"
                        class="w-full border rounded-lg p-3"
                        placeholder="Masukkan porsi per orang">
                </div>

                <!-- Total Porsi (AUTO) -->
                <div class="md:col-span-2">
                    <label>Total Porsi (Otomatis)</label>
                    <input type="number" name="total_porsi"
                        value="{{ old('total_porsi') }}"
                        class="w-full border rounded-lg p-3 bg-gray-100"
                        readonly>
                </div>

            </div> 

            <!-- Button -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('management_posko.kebutuhan_harian.index') }}" 
                   class="px-4 py-2 bg-gray-300 rounded-lg">
                    Batal
                </a>

                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg">
                    Simpan Data
                </button>
            </div>

        </form>
    </div>

    <!-- Script Auto Hitung -->
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

        document.addEventListener('DOMContentLoaded', hitungTotal);
    </script>

@endsection