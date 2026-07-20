@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6">

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Tambah Pengambilan</h2>
        <p class="text-gray-500 text-sm">Tambahkan data pengambilan barang dari posko</p>
    </div>

    {{-- Error Flash --}}
    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-4 rounded-lg">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.management_barang.pengambilan.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- DATA UTAMA --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            {{-- Bencana --}}
            <div>
                <label class="block mb-2 font-medium">Bencana</label>
                <select name="bencana_id" class="w-full border rounded-lg px-4 py-2" required>
                    <option value="">-- Pilih Bencana --</option>
                    @foreach($bencana as $item)
                        <option value="{{ $item->id }}" {{ old('bencana_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->nama_bencana ?? 'Bencana '.$item->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Petugas --}}
            <div>
                <label class="block mb-2 font-medium">Petugas</label>
                <select name="petugas_id" class="w-full border rounded-lg px-4 py-2" required>
                    <option value="">-- Pilih Petugas --</option>
                    @foreach($petugas as $item)
                        <option value="{{ $item->id }}" {{ old('petugas_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->nama_petugas }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Posko --}}
            <div>
                <label class="block mb-2 font-medium">Posko</label>
                <select name="posko_id" class="w-full border rounded-lg px-4 py-2" required>
                    <option value="">-- Pilih Posko --</option>
                    @foreach($posko as $item)
                        <option value="{{ $item->id }}" {{ old('posko_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->nama_posko }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tanggal --}}
            <div>
                <label class="block mb-2 font-medium">Tanggal Pengambilan</label>
                <input type="date" 
                       name="tanggal_pengambilan" 
                       value="{{ old('tanggal_pengambilan') }}"
                       class="w-full border rounded-lg px-4 py-2" 
                       required>
            </div>
        </div>

        {{-- Tujuan --}}
        <div class="mt-5">
            <label class="block mb-2 font-medium">Tujuan</label>
            <textarea name="tujuan" 
                      rows="3" 
                      class="w-full border rounded-lg px-4 py-2"
                      placeholder="Masukkan tujuan pengambilan" 
                      required>{{ old('tujuan') }}</textarea>
        </div>

        {{-- Gambar Dokumen Utama (Sesuai Controller Validasi Data Tunggal) --}}
        <div class="mt-5">
            <label class="block mb-2 font-medium">Gambar Dokumen Pendukung</label>
            <input type="file" 
                   name="gambar" 
                   accept="image/*" 
                   class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none">
        </div>

        {{-- DATA BARANG --}}
        <div class="mt-8">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-semibold">Data Barang</h3>
                <button type="button" 
                        id="tambahRow" 
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                    + Tambah Barang
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border text-sm" id="tableBarang">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Barang</th>
                            <th class="p-3 text-center w-24">Stok</th>
                            <th class="p-3 text-center w-32">Jumlah Ambil</th>
                            <th class="p-3 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            {{-- Barang --}}
                            <td class="p-3">
                                <select name="barang_id[]" 
                                        class="w-full border rounded-lg px-3 py-2 barang-select" 
                                        required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($barang as $item)
                                        <option value="{{ $item->id_barang }}" data-stok="{{ $item->stok }}">
                                            {{ $item->nama_barang }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            {{-- Stok Indikator --}}
                            <td class="p-3 text-center font-medium text-gray-600 stok-text">0</td>

                            {{-- Jumlah Ambil --}}
                            <td class="p-3">
                                <input type="number" 
                                       name="jumlah_ambil[]" 
                                       min="1" 
                                       required
                                       class="w-full border rounded-lg px-3 py-2 text-center">
                            </td>

                            {{-- Aksi Hapus Row --}}
                            <td class="p-3 text-center">
                                <button type="button" class="hapusRow text-red-500 hover:text-red-700 font-medium">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="mt-8 flex gap-3">
            <button type="submit" class="bg-indigo-700 text-white px-6 py-2 rounded-lg hover:bg-indigo-800 transition">
                Simpan
            </button>
            <a href="{{ route('admin.management_barang.pengambilan.index') }}" 
               class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
                Kembali
            </a>
        </div>
    </form>
</div>

{{-- SCRIPT MANAGEMENT LOGIC --}}
<script>
    // Tambah Baris Barang Baru
    document.getElementById('tambahRow').addEventListener('click', function () {
        const tableBody = document.querySelector('#tableBarang tbody');
        const firstRow = tableBody.rows[0];
        const newRow = firstRow.cloneNode(true);

        // Reset semua value di baris baru agar kosong bersih
        newRow.querySelectorAll('input').forEach(input => input.value = '');
        newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
        newRow.querySelector('.stok-text').innerText = '0';

        tableBody.appendChild(newRow);
    });

    // Deteksi Otomatis Nilai Stok Berdasarkan Dropdown Barang
    document.addEventListener('change', function(e) {
        if(e.target.classList.contains('barang-select')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const stok = selectedOption.getAttribute('data-stok') || '0';
            
            e.target.closest('tr').querySelector('.stok-text').innerText = stok;
        }
    });

    // Hapus Baris Terpilih
    document.addEventListener('click', function(e) {
        if(e.target.classList.contains('hapusRow')) {
            const rows = document.querySelectorAll('#tableBarang tbody tr');
            if(rows.length > 1) {
                e.target.closest('tr').remove();
            } else {
                alert('Minimal harus ada satu data barang yang dimasukkan.');
            }
        }
    });
</script>
@endsection