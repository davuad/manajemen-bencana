@extends('layouts.app')

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold">Edit Data Posko</h2>
        <p class="text-gray-500 text-sm">
           Perbarui data posko untuk memastikan informasi tetap akurat
        </p>
    </div>

    <div class="bg-white rounded-xl p-5 m-3 mt-5">
        <form action="{{ route('management_posko.posko.update', $posko->id_posko) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama Posko -->
            <div>
                <label class="block font-medium">Nama Posko *</label>
                <input type="text" name="nama_posko"
                    value="{{ old('nama_posko', $posko->nama_posko) }}"
                    class="w-full border rounded-lg p-3"
                    placeholder="Contoh: Posko Balai Desa Suka Maju">
            </div>

            <!-- Tanggal & Desa -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label>Tanggal Dibuat *</label>
                    <input type="date" name="tanggal_dibuat"
                        value="{{ old('tanggal_dibuat', $posko->tanggal_dibuat) }}"
                        class="w-full border rounded-lg p-3">
                </div>

                <div>
                    <label>Desa Terdampak *</label>
                    <select name="id_desa" class="w-full border rounded-lg p-3">
                        <option value="">Pilih Desa</option>
                        @foreach($desa as $d)
                            <option value="{{ $d->id_desa }}"
                                {{ $posko->id_desa == $d->id_desa ? 'selected' : '' }}>
                                {{ $d->nama_desa }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Pengaduan -->
            <div>
                <label>Tautkan ke Laporan Bencana *</label>
                <select name="id_pengaduan" class="w-full border rounded-lg p-3">
                    <option value="">Pilih Laporan</option>
                    @foreach($pengaduan as $p)
                        <option value="{{ $p->id_pengaduan }}"
                            {{ $posko->id_pengaduan == $p->id_pengaduan ? 'selected' : '' }}>
                            {{ $p->deskripsi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Lokasi -->
            <div>
                <label>Alamat Lengkap / Lokasi *</label>
                <textarea name="lokasi"
                    class="w-full border rounded-lg p-3"
                    rows="4"
                    placeholder="Masukkan detail alamat...">{{ old('lokasi', $posko->lokasi) }}</textarea>
            </div>

            <!-- Button -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('management_posko.posko.index') }}"
                   class="px-4 py-2 bg-gray-300 rounded-lg">
                    Batal
                </a>

                <button type="submit" class="px-6 py-2 bg-yellow-500 text-white rounded-lg">
                    Update Data
                </button>
            </div>
        </form>
    </div> 
@endsection