@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6">

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Edit Data Pengambilan</h2>
        <p class="text-gray-500 text-sm">Perbarui data pengambilan barang dari posko</p>
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

    <form method="POST" action="{{ route('admin.management_barang.pengambilan.update', $data->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- DATA UTAMA --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            {{-- Bencana --}}
            <div>
                <label class="block mb-2 font-medium">Bencana</label>
                <select name="bencana_id" class="w-full border rounded-lg px-4 py-2" required>
                    <option value="">-- Pilih Bencana --</option>
                    @foreach($bencana as $item)
                        <option value="{{ $item->id }}" {{ old('bencana_id', $data->bencana_id) == $item->id ? 'selected' : '' }}>
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
                        <option value="{{ $item->id }}" {{ old('petugas_id', $data->petugas_id) == $item->id ? 'selected' : '' }}>
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
                        <option value="{{ $item->id }}" {{ old('posko_id', $data->posko_id) == $item->id ? 'selected' : '' }}>
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
                       value="{{ old('tanggal_pengambilan', $data->tanggal_pengambilan) }}"
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
                      required>{{ old('tujuan', $data->tujuan) }}</textarea>
        </div>

        {{-- Gambar Dokumen Utama (Sesuai Validasi Data Tunggal di Backend) --}}
        <div class="mt-5">
            <label class="block mb-2 font-medium">Gambar Dokumen Pendukung</label>
            <input type="file" 
                   name="gambar" 
                   accept="image/*" 
                   class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none">
            
            {{-- Menampilkan gambar global saat ini jika ada --}}
            @if(isset($data->gambar) && $data->gambar)
                <div class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                    <span class="inline-block w-2 h-2 rounded-full bg-green-500"></span>
                    <a href="{{ asset('storage/' . $data->gambar) }}" target="_blank" class="text-indigo-600 hover:underline font-medium">
                        Lihat Gambar Nota/Dokumen Saat Ini
                    </a>
                </div>
            @endif
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
                        {{-- Jika validasi gagal --}}
                        @if(old('barang_id'))
                            @foreach(old('barang_id') as $index => $oldBarangId)
                                <tr>
                                    <td class="p-3">
                                        <select name="barang_id[]" class="w-full border rounded-lg px-3 py-2 barang-select" required>
                                            <option value="">-- Pilih Barang --</option>
                                            @foreach($barang as $br)
                                                <option value="{{ $br->id_barang }}" data-stok="{{ $br->stok }}" {{ $oldBarangId == $br->id_barang ? 'selected' : '' }}>
                                                    {{ $br->nama_barang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-3 text-center font-medium text-gray-600 stok-text">
                                        @php $currentBarang = $barang->firstWhere('id_barang', $oldBarangId); @endphp
                                        {{ $currentBarang ? $currentBarang->stok : 0 }}
                                    </td>
                                    <td class="p-3">
                                        <input type="number" name="jumlah_ambil[]" value="{{ old('jumlah_ambil.'.$index, 1) }}" min="1" required class="w-full border rounded-lg px-3 py-2 text-center jumlah-input">
                                    </td>
                                    <td class="p-3 text-center">
                                        <button type="button" class="hapusRow text-red-500 hover:text-red-700 font-medium">Hapus</button>
                                    </td>
                                </tr>
                            @endforeach

                        {{-- Data dari database --}}
                        @elseif(isset($barangPengambilan) && $barangPengambilan->count() > 0)
                            @foreach($barangPengambilan as $item)
                                <tr>
                                    <td class="p-3">
                                        <select name="barang_id[]" class="w-full border rounded-lg px-3 py-2 barang-select" required>
                                            <option value="">-- Pilih Barang --</option>
                                            @foreach($barang as $br)
                                                <option value="{{ $br->id_barang }}" data-stok="{{ $br->stok }}" {{ $item->barang_id == $br->id_barang ? 'selected' : '' }}>
                                                    {{ $br->nama_barang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-3 text-center font-medium text-gray-600 stok-text">
                                        {{ $item->barang->stok ?? 0 }}
                                    </td>
                                    <td class="p-3">
                                        <input type="number" name="jumlah_ambil[]" value="{{ $item->jumlah_ambil }}" min="1" required class="w-full border rounded-lg px-3 py-2 text-center jumlah-input">
                                    </td>
                                    <td class="p-3 text-center">
                                        <button type="button" class="hapusRow text-red-500 hover:text-red-700 font-medium">Hapus</button>
                                    </td>
                                </tr>
                            @endforeach

                        {{-- Fallback jika kosong --}}
                        @else
                            <tr>
                                <td class="p-3">
                                    <select name="barang_id[]" class="w-full border rounded-lg px-3 py-2 barang-select" required>
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach($barang as $br)
                                            <option value="{{ $br->id_barang }}" data-stok="{{ $br->stok }}" {{ (isset($data->barang_id) && $data->barang_id == $br->id_barang) ? 'selected' : '' }}>
                                                {{ $br->nama_barang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-3 text-center font-medium text-gray-600 stok-text">
                                    {{ $data->barang->stok ?? 0 }}
                                </td>
                                <td class="p-3">
                                    <input type="number" name="jumlah_ambil[]" value="{{ $data->jumlah_ambil ?? 1 }}" min="1" required class="w-full border rounded-lg px-3 py-2 text-center jumlah-input">
                                </td>
                                <td class="p-3 text-center">
                                    <button type="button" class="hapusRow text-red-500 hover:text-red-700 font-medium">Hapus</button>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Status --}}
        <div class="mt-6">
            <label class="block font-medium mb-3">Status</label>
            <div class="flex gap-3 flex-wrap">
                @foreach(['Ditangani','Selesai','Dibatalkan'] as $s)
                <label class="cursor-pointer">
                    <input type="radio"
                           name="status"
                           value="{{ $s }}"
                           {{ old('status', $data->status) == $s ? 'checked' : '' }}
                           class="hidden peer">
                    <span class="px-4 py-2 rounded-full font-semibold bg-gray-100 text-gray-700 peer-checked:bg-indigo-600 peer-checked:text-white transition">
                        {{ $s }}
                    </span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="mt-8 flex gap-3">
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">
                Update
            </button>
            <a href="{{ route('admin.management_barang.pengambilan.index') }}" 
                class="bg-gray-500 text-white px-4 py-2 rounded">
                Kembali
            </a>
        </div>
    </form>
</div>

{{-- SCRIPT MANAGEMENT LOGIC --}}
<script>
    // Tambah Row Baru
    document.getElementById('tambahRow').addEventListener('click', function () {
        let tableBody = document.querySelector('#tableBarang tbody');
        let firstRow = tableBody.rows[0];
        let newRow = firstRow.cloneNode(true);

        // Reset input data di baris baru agar kosong bersih
        newRow.querySelectorAll('input').forEach(input => {
            input.value = '';
            input.readOnly = false;
            input.classList.remove('bg-gray-100', 'cursor-not-allowed');
        });

        // Reset dropdown select di baris baru
        newRow.querySelectorAll('select').forEach(select => {
            select.selectedIndex = 0;
        });

        // Reset teks stok ke 0
        newRow.querySelector('.stok-text').innerText = '0';

        // Konfigurasi nilai input berdasarkan status aktif saat ini
        let status = document.querySelector('input[name="status"]:checked')?.value;
        let inputJumlah = newRow.querySelector('.jumlah-input');
        if (inputJumlah) {
            inputJumlah.value = (status === 'Dibatalkan') ? 0 : 1;
            inputJumlah.readOnly = (status === 'Dibatalkan');
            if(status === 'Dibatalkan') inputJumlah.classList.add('bg-gray-100', 'cursor-not-allowed');
        }

        let hapusBtn = newRow.querySelector('.hapusRow');
        if (hapusBtn) {
            hapusBtn.disabled = false;
            hapusBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        tableBody.appendChild(newRow);
        checkStatusBatal();
    });

    // Sinkronisasi Indikator Stok Barang Dinamis
    document.addEventListener('change', function(e) {
        if(e.target.classList.contains('barang-select')) {
            let selectedOption = e.target.options[e.target.selectedIndex];
            let stok = selectedOption.getAttribute('data-stok') || '0';
            
            e.target.closest('tr').querySelector('.stok-text').innerText = stok;
        }
    });

    // Hapus Row Pilihan
    document.addEventListener('click', function(e) {
        if(e.target.classList.contains('hapusRow')) {
            let rows = document.querySelectorAll('#tableBarang tbody tr');
            if(rows.length > 1) {
                e.target.closest('tr').remove();
            } else {
                alert('Minimal harus ada satu data barang.');
            }
        }
    });

    // Validasi & Proteksi Kolom Jumlah Jika Status Dibatalkan
    function checkStatusBatal() {
        let status = document.querySelector('input[name="status"]:checked')?.value;
        let jumlahInputs = document.querySelectorAll('.jumlah-input');
        let tombolTambah = document.getElementById('tambahRow');
        let tombolHapus = document.querySelectorAll('.hapusRow');

        if (status === 'Dibatalkan') {
            jumlahInputs.forEach(input => {
                input.value = 0;
                input.readOnly = true;
                input.classList.add('bg-gray-100', 'cursor-not-allowed');
            });
            tombolTambah.disabled = true;
            tombolTambah.classList.add('opacity-50', 'cursor-not-allowed');
            tombolHapus.forEach(btn => {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            });
        } else {
            jumlahInputs.forEach(input => {
                if (input.value == 0) {
                    input.value = 1;
                }
                input.readOnly = false;
                input.classList.remove('bg-gray-100', 'cursor-not-allowed');
            });
            tombolTambah.disabled = false;
            tombolTambah.classList.remove('opacity-50', 'cursor-not-allowed');
            tombolHapus.forEach(btn => {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            });
        }
    }

    document.querySelectorAll('input[name="status"]').forEach(radio => {
        radio.addEventListener('change', checkStatusBatal);
    });

    document.addEventListener('DOMContentLoaded', function() {
        checkStatusBatal();
    });
</script>
@endsection