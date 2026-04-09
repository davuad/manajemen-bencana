@extends('layouts.app')

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold">Edit Data Dapur Umum</h2>
        <p class="text-gray-500 text-sm">
           Perbarui data dapur umum dengan informasi terbaru
        </p>
    </div>

    <div class="bg-white rounded-xl p-5 m-3 mt-5">
        <form action="{{ route('management_posko.dapur_umum.update', $dapur->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Pilih Posko -->
            <div>
                <label class="block font-medium">Pilih Posko *</label>
                <select name="posko_id" class="w-full border rounded-lg p-3">
                    <option value="">Pilih Posko</option>
                    @foreach($posko as $p)
                        <option value="{{ $p->id }}" 
                            {{ $dapur->posko_id == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_posko }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Nama Dapur Umum -->
            <div>
                <label class="block font-medium">Nama Dapur Umum *</label>
                <input type="text" name="nama_dapur_umum"
                    value="{{ old('nama_dapur_umum', $dapur->nama_dapur_umum) }}"
                    class="w-full border rounded-lg p-3"
                    placeholder="Contoh: Dapur Umum 2">
            </div>

            <!-- Kapasitas Warga -->
            <div>
                <label class="block font-medium">Kapasitas Warga *</label>
                <input type="number" name="kapasitas_warga"
                    value="{{ old('kapasitas_warga', $dapur->kapasitas_warga) }}"
                    class="w-full border rounded-lg p-3"
                    placeholder="Masukkan kapasitas maksimal warga">
            </div>

            <!-- Jumlah Warga -->
            <div>
                <label class="block font-medium">Jumlah Warga Saat Ini *</label>
                <input type="number" name="jumlah_warga"
                    value="{{ old('jumlah_warga', $dapur->jumlah_warga) }}"
                    class="w-full border rounded-lg p-3"
                    placeholder="Masukkan jumlah warga saat ini">
            </div>

            <!-- Penanggung Jawab -->
            <div>
                <label class="block font-medium">Penanggung Jawab *</label>
                <input type="text" name="penanggung_jawab"
                    value="{{ old('penanggung_jawab', $dapur->penanggung_jawab) }}"
                    class="w-full border rounded-lg p-3"
                    placeholder="Masukkan nama penanggung jawab">
            </div>
            </div>

            <!-- Button -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('management_posko.dapur_umum.index') }}" class="px-4 py-2 bg-gray-300 rounded-lg">
                    Batal
                </a>

                <button type="submit" class="px-6 py-2 bg-yellow-500 text-white rounded-lg">
                    Update Data
                </button>
            </div>
        </form>
    </div> 
@endsection