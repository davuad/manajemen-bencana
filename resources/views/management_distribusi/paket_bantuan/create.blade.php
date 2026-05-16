@extends('layouts.app')

@section('content')
<div class="mx-3">
    <h2 class="text-xl font-bold">Tambah Paket Bantuan</h2>
    <p class="text-gray-500 text-sm">
        Lengkapi data paket bantuan untuk mempermudah proses distribusi kepada warga terdampak
    </p>
</div>

<div class="bg-white rounded-xl p-5 m-3 mt-5">
    <form action="{{ route('management_distribusi.paket_bantuan.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Nama Paket -->
            <div class="md:col-span-2">
                <label class="block font-medium">Nama Paket *</label>
                <input type="text" name="nama_paket"
                    class="w-full border rounded-lg p-3"
                    placeholder="Contoh: Paket Pasca Bencana Tahap 1"
                    value="{{ old('nama_paket') }}">
            </div>

            <!-- Posko -->
            <div>
                <label class="block font-medium">Posko *</label>
                <select name="posko_id" class="w-full border rounded-lg p-3">
                    <option value="">Pilih Posko</option>
                    @foreach($posko as $p)
                    <option value="{{ $p->id }}" {{ old('posko_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->nama_posko }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div>
                <label class="block font-medium">Status *</label>
                <select name="status" class="w-full border rounded-lg p-3">
                    <option value="">Pilih Status</option>
                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="non aktif" {{ old('status') == 'non aktif' ? 'selected' : '' }}>Non Aktif</option>
                </select>
            </div>

            <!-- Keterangan -->
            <div class="md:col-span-2">
                <label class="block font-medium">Keterangan</label>
                <textarea name="keterangan"
                    class="w-full border rounded-lg p-3"
                    rows="3"
                    placeholder="Masukkan keterangan paket bantuan...">{{ old('keterangan') }}</textarea>
            </div>

        </div>

        <!-- Button -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('management_distribusi.paket_bantuan.index') }}" class="px-4 py-2 bg-gray-300 rounded-lg">
                Batal
            </a>

            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg">
                Simpan Paket Bantuan
            </button>
        </div>
    </form>
</div>
@endsection