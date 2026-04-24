@extends('layouts.app')

@section('content')
<div class="mx-3">
    <h2 class="text-xl font-bold">Tambah Data Distribusi</h2>
    <p class="text-gray-500 text-sm">
        Lengkapi data distribusi untuk memastikan penyaluran bantuan berjalan lancar
    </p>
</div>

<div class="bg-white rounded-xl p-5 m-3 mt-5">

    {{-- 🔴 ERROR VALIDATION --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <b>Terjadi kesalahan:</b>
            <ul class="list-disc ml-5 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('management_distribusi.distribusi.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Barang Keluar -->
            <div>
                <label>Barang Keluar *</label>
                <select name="barang_keluar_id" class="w-full border rounded-lg p-3" required>
                    <option value="" disabled selected>Pilih Barang Keluar</option>
                    @foreach($barangKeluar as $bk)
                        <option value="{{ $bk->id }}" {{ old('barang_keluar_id') == $bk->id ? 'selected' : '' }}>
                            ID {{ $bk->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Bencana -->
            <div>
                <label>Bencana *</label>
                <select name="bencana_id" class="w-full border rounded-lg p-3" required>
                    <option value="" disabled selected>Pilih Bencana</option>
                    @foreach($bencana as $b)
                        <option value="{{ $b->id }}" {{ old('bencana_id') == $b->id ? 'selected' : '' }}>
                            ID {{ $b->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Posko -->
            <div>
                <label>Posko *</label>
                <select name="posko_id" class="w-full border rounded-lg p-3" required>
                    <option value="" disabled selected>Pilih Posko</option>
                    @foreach($posko as $p)
                        <option value="{{ $p->id }}" {{ old('posko_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_posko }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tanggal -->
            <div>
                <label>Tanggal Distribusi *</label>
                <input type="date" name="tanggal_distribusi"
                    value="{{ old('tanggal_distribusi') }}"
                    class="w-full border rounded-lg p-3" required>
            </div>

            <!-- Lokasi -->
            <div class="md:col-span-2">
                <label>Lokasi Distribusi *</label>
                <input type="text" name="lokasi_distribusi"
                    value="{{ old('lokasi_distribusi') }}"
                    class="w-full border rounded-lg p-3"
                    placeholder="Contoh: Desa Sukamaju RT 01 RW 02" required>
            </div>

            <!-- Kendaraan -->
            <div>
                <label>Kendaraan *</label>
                <input type="text" name="kendaraan"
                    value="{{ old('kendaraan') }}"
                    class="w-full border rounded-lg p-3"
                    placeholder="Contoh: Truk / Pickup" required>
            </div>

            <!-- Supir -->
            <div>
                <label>Nama Supir *</label>
                <input type="text" name="nama_supir"
                    value="{{ old('nama_supir') }}"
                    class="w-full border rounded-lg p-3"
                    placeholder="Nama supir" required>
            </div>

            <!-- Nomor Kendaraan -->
            <div>
                <label>Nomor Kendaraan *</label>
                <input type="text" name="nomor_kendaraan"
                    value="{{ old('nomor_kendaraan') }}"
                    class="w-full border rounded-lg p-3"
                    placeholder="Contoh: R 1234 AB" required>
            </div>

            <!-- Kategori -->
            <div>
                <label>Kategori Distribusi *</label>
                <input type="text" name="kategori_distribusi"
                    value="{{ old('kategori_distribusi') }}"
                    class="w-full border rounded-lg p-3"
                    placeholder="Contoh: Bantuan Logistik" required>
            </div>

            <!-- Status -->
            <div>
                <label>Status *</label>
                <select name="status" class="w-full border rounded-lg p-3" required>
                    <option value="" disabled selected>Pilih Status</option>
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="dikirim" {{ old('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                    <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <!-- Keterangan -->
            <div class="md:col-span-2">
                <label>Keterangan</label>
                <textarea name="keterangan"
                    class="w-full border rounded-lg p-3"
                    rows="3"
                    placeholder="Tambahkan keterangan jika diperlukan...">{{ old('keterangan') }}</textarea>
            </div>

        </div>

        <!-- BUTTON -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('management_distribusi.distribusi.index') }}"
               class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                Batal
            </a>

            <button type="submit"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Simpan Data
            </button>
        </div>

    </form>
</div>
@endsection