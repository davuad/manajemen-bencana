@extends('layouts.app')

@section('content')
<div class="mx-3">
    <h2 class="text-xl font-bold">Edit Data Distribusi</h2>
    <p class="text-gray-500 text-sm">
        Perbarui data distribusi agar tetap akurat
    </p>
</div>

<div class="bg-white rounded-xl p-5 m-3 mt-5">
    <form action="{{ route('management_distribusi.distribusi.update', $distribusi->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- RELASI -->
        <div class="grid grid-cols-3 gap-4">

            <div>
                <label>Barang Keluar</label>
                <select name="barang_keluar_id" class="w-full border rounded-lg p-3">
                    <option value="">Pilih</option>
                    @foreach($barangKeluar as $bk)
                        <option value="{{ $bk->id }}"
                            {{ $distribusi->barang_keluar_id == $bk->id ? 'selected' : '' }}>
                            ID {{ $bk->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Bencana</label>
                <select name="bencana_id" class="w-full border rounded-lg p-3">
                    <option value="">Pilih</option>
                    @foreach($bencana as $b)
                        <option value="{{ $b->id }}"
                            {{ $distribusi->bencana_id == $b->id ? 'selected' : '' }}>
                            ID {{ $b->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Posko</label>
                <select name="posko_id" class="w-full border rounded-lg p-3">
                    <option value="">Pilih</option>
                    @foreach($posko as $p)
                        <option value="{{ $p->id }}"
                            {{ $distribusi->posko_id == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_posko }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <!-- INPUT -->
        <div class="grid grid-cols-2 gap-4">

            <div>
                <label>Tanggal</label>
                <input type="date" name="tanggal_distribusi"
                    value="{{ old('tanggal_distribusi', $distribusi->tanggal_distribusi) }}"
                    class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label>Lokasi</label>
                <input type="text" name="lokasi_distribusi"
                    value="{{ old('lokasi_distribusi', $distribusi->lokasi_distribusi) }}"
                    class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label>Kendaraan</label>
                <input type="text" name="kendaraan"
                    value="{{ old('kendaraan', $distribusi->kendaraan) }}"
                    class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label>Nama Supir</label>
                <input type="text" name="nama_supir"
                    value="{{ old('nama_supir', $distribusi->nama_supir) }}"
                    class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label>No Kendaraan</label>
                <input type="text" name="nomor_kendaraan"
                    value="{{ old('nomor_kendaraan', $distribusi->nomor_kendaraan) }}"
                    class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label>Kategori</label>
                <input type="text" name="kategori_distribusi"
                    value="{{ old('kategori_distribusi', $distribusi->kategori_distribusi) }}"
                    class="w-full border rounded-lg p-3">
            </div>

        </div>

        <!-- KETERANGAN -->
        <div>
            <label>Keterangan</label>
            <textarea name="keterangan"
                class="w-full border rounded-lg p-3"
                rows="3">{{ old('keterangan', $distribusi->keterangan) }}</textarea>
        </div>

        <!-- STATUS -->
        <div>
            <label>Status</label>
            <select name="status" class="w-full border rounded-lg p-3">
                <option value="pending" {{ $distribusi->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="dikirim" {{ $distribusi->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                <option value="selesai" {{ $distribusi->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

        <!-- BUTTON -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('management_distribusi.distribusi.index') }}"
               class="px-4 py-2 bg-gray-300 rounded-lg">
                Batal
            </a>

            <button type="submit"
                class="px-6 py-2 bg-yellow-500 text-white rounded-lg">
                Update Data
            </button>
        </div>

    </form>
</div>
@endsection