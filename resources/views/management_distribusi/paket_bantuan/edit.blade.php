@extends('layouts.app')

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold">Edit Paket Bantuan</h2>
        <p class="text-gray-500 text-sm">
            Perbarui data paket bantuan untuk memastikan informasi tetap akurat
        </p>
    </div>

    <div class="bg-white rounded-xl p-5 m-3 mt-5">
        <form action="{{ route('admin.management_distribusi.paket_bantuan.update', $paket_bantuan->id) }}" method="POST"
            class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <!-- Nama Paket -->
                <div>
                    <label class="block font-medium">Nama Paket *</label>
                    <input type="text" name="nama_paket" value="{{ old('nama_paket', $paket_bantuan->nama_paket) }}"
                        class="w-full border rounded-lg p-3" placeholder="Contoh: Paket Pasca Bencana Tahap 1">
                </div>

                <!-- Status -->
                <div>
                    <label class="block font-medium mb-2">Status *</label>

                    <div class="flex gap-6">

                        <!-- Aktif -->
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="aktif"
                                {{ old('status', $paket_bantuan->status) == 'aktif' ? 'checked' : '' }}
                                class="w-4 h-4 text-green-600">

                            <span class="text-green-700 font-medium">Aktif</span>
                        </label>

                        <!-- Non Aktif -->
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="non aktif"
                                {{ old('status', $paket_bantuan->status) == 'non aktif' ? 'checked' : '' }}
                                class="w-4 h-4 text-red-600">

                            <span class="text-red-600 font-medium">Non Aktif</span>
                        </label>

                    </div>
                </div>
            </div>

            <!-- Posko -->
            <div>
                <label class="block font-medium">Posko *</label>
                <select name="posko_id" class="w-full border rounded-lg p-3">
                    <option value="">Pilih Posko</option>
                    @foreach ($posko as $p)
                        <option value="{{ $p->id }}"
                            {{ old('posko_id', $paket_bantuan->posko_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_posko }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block font-medium">Keterangan</label>
                <textarea name="keterangan" class="w-full border rounded-lg p-3" rows="4"
                    placeholder="Masukkan keterangan paket bantuan...">{{ old('keterangan', $paket_bantuan->keterangan) }}</textarea>
            </div>

            <!-- Button -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.management_distribusi.paket_bantuan.index') }}" class="px-4 py-2 bg-gray-300 rounded-lg">
                    Batal
                </a>

                <button type="submit" class="px-6 py-2 bg-yellow-500 text-white rounded-lg">
                    Update Data
                </button>
            </div>
        </form>
    </div>
@endsection
