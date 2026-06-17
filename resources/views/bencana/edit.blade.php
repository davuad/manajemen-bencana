@extends('layouts.app')

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold">Edit Data Bencana</h2>
        <p class="text-gray-500 text-sm">
            Perbarui data bencana
        </p>
    </div>

    <div class="bg-white rounded-xl p-5 m-3 mt-5">

        <form action="{{ route('admin.bencana.update', $bencana->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- NAMA BENCANA --}}
                <div>
                    <label class="block font-medium">Nama Bencana *</label>
                    <input type="text" name="nama_bencana" value="{{ old('nama_bencana', $bencana->nama_bencana) }}"
                        class="w-full border rounded-lg p-3">
                </div>

                {{-- KATEGORI --}}
                <div>
                    <label class="block font-medium">Kategori *</label>
                    <select name="kategori_id" class="w-full border rounded-lg p-3">
                        @foreach ($kategori as $k)
                            <option value="{{ $k->id }}" {{ $bencana->kategori_id == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- DESA --}}
                <div>
                    <label class="block font-medium">Desa</label>
                    <select name="desa_id" class="w-full border rounded-lg p-3">
                        <option value="">-- Pilih Desa --</option>
                        @foreach ($desa as $d)
                            <option value="{{ $d->id }}" {{ $bencana->desa_id == $d->id ? 'selected' : '' }}>
                                {{ $d->nama_desa }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- PENGADUAN --}}
                <div>
                    <label class="block font-medium">Pengaduan</label>
                    <select name="pengaduan_id" class="w-full border rounded-lg p-3">
                        <option value="">-- Pilih Pengaduan --</option>
                        @foreach ($pengaduan as $p)
                            <option value="{{ $p->id }}" {{ $bencana->pengaduan_id == $p->id ? 'selected' : '' }}>
                                {{ $p->deskripsi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- TANGGAL --}}
                <div>
                    <label class="block font-medium">Tanggal *</label>
                    <input type="date" name="tanggal" value="{{ $bencana->tanggal }}"
                        class="w-full border rounded-lg p-3">
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="block font-medium">Status Bencana *</label>
                    <select name="status_bencana" class="w-full border rounded-lg p-3">
                        <option value="berlangsung" {{ $bencana->status_bencana == 'berlangsung' ? 'selected' : '' }}>
                            🟠 Berlangsung
                        </option>

                        <option value="selesai" {{ $bencana->status_bencana == 'selesai' ? 'selected' : '' }}>
                            🟢 Selesai
                        </option>
                    </select>
                </div>

                {{-- KERUSAKAN --}}
                <div>
                    <label class="block font-medium">Tingkat Kerusakan *</label>
                    <select name="tingkat_kerusakan" class="w-full border rounded-lg p-3">
                        <option value="ringan" {{ $bencana->tingkat_kerusakan == 'ringan' ? 'selected' : '' }}>Ringan
                        </option>
                        <option value="sedang" {{ $bencana->tingkat_kerusakan == 'sedang' ? 'selected' : '' }}>Sedang
                        </option>
                        <option value="parah" {{ $bencana->tingkat_kerusakan == 'parah' ? 'selected' : '' }}>Parah
                        </option>
                    </select>
                </div>

            </div>

            <div class="flex justify-end mt-6 gap-3">
                <a href="{{ route('admin.bencana.index') }}" class="bg-gray-300 px-4 py-2 rounded">
                    Batal
                </a>

                <button class="bg-yellow-500 text-white px-6 py-2 rounded">
                    Update
                </button>
            </div>

        </form>

    </div>
@endsection
