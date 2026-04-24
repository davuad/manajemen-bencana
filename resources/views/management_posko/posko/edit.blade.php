@extends('layouts.app')

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold">Edit Data Posko</h2>
        <p class="text-gray-500 text-sm">
           Perbarui data posko untuk memastikan informasi tetap akurat
        </p>
    </div>

    <div class="bg-white rounded-xl p-5 m-3 mt-5">
        <form action="{{ route('management_posko.posko.update', $posko->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <!-- Nama Posko -->
                <div>
                    <label class="block font-medium">Nama Posko *</label>
                    <input type="text" name="nama_posko"
                        value="{{ old('nama_posko', $posko->nama_posko) }}"
                        class="w-full border rounded-lg p-3"
                        placeholder="Contoh: Posko Balai Desa Suka Maju">
                </div>
                <div>
                    <label class="block font-medium">Status *</label>
                    <div class="flex gap-3 mt-4">
                        
                        <!-- Aktif -->
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="aktif" class="hidden peer"
                                {{ $posko->status == 'aktif' ? 'checked' : '' }}>
                            
                            <span class="px-6 py-3 rounded-full font-semibold transition-all duration-200
                                peer-checked:bg-green-400 peer-checked:text-green-900
                                bg-green-100 text-green-400 opacity-60">
                                Aktif
                            </span>
                        </label>

                        <!-- Tidak Aktif -->
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="tidak aktif" class="hidden peer"
                                {{ $posko->status == 'tidak aktif' ? 'checked' : '' }}>
                            
                            <span class="px-6 py-3 rounded-full font-semibold transition-all duration-200
                                peer-checked:bg-red-200 peer-checked:text-red-700
                                bg-red-100 text-red-400 opacity-60">
                                Tidak Aktif
                            </span>
                        </label>

                    </div>
                </div>
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
                    <select name="desa_id" class="w-full border rounded-lg p-3">
                        <option value="">Pilih Desa</option>
                        @foreach($desa as $d)
                            <option value="{{ $d->id }}"
                                {{ $posko->desa_id == $d->id ? 'selected' : '' }}>
                                {{ $d->nama_desa }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Pengaduan -->
            <div>
                <label>Tautkan ke Laporan Bencana *</label>
                <select name="pengaduan_bencana_id" class="w-full border rounded-lg p-3">
                    <option value="">Pilih Laporan</option>
                    @foreach($pengaduan as $p)
                        <option value="{{ $p->id }}"
                            {{ $posko->pengaduan_bencana_id == $p->id ? 'selected' : '' }}>
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