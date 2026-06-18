@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="bg-white rounded-xl shadow p-6">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold">Form Penjemputan Anak</h2>

            <a href="{{ route('admin.penjemputan.index') }}"
               class="bg-gray-500 text-white px-4 py-2 rounded-lg">
                Kembali
            </a>
        </div>

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.penjemputan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="anak_id" value="{{ $anak->id }}">

            {{-- STATUS JANGAN AUTO VALID --}}
            <input type="hidden" name="status_verifikasi" value="menunggu">

            {{-- DATA ANAK --}}
            <div class="border rounded-lg p-4 mb-6">
                <h3 class="font-bold text-lg mb-3">Data Anak</h3>

                <div class="flex gap-4">
                    @if($anak->foto_anak)
                        <img src="{{ asset('storage/'.$anak->foto_anak) }}"
                             class="w-28 h-28 object-cover rounded">
                    @endif

                    <div class="text-sm space-y-1">
                        <p><b>{{ $anak->nama_anak }}</b></p>
                        <p>Umur: {{ $anak->umur ?? '-' }}</p>
                        <p>Jenis Kelamin: {{ $anak->jenis_kelamin }}</p>
                        <p>Lokasi: {{ $anak->lokasi_ditemukan }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- PENJEMPUT --}}
                <div class="border rounded-lg p-4">
                    <h3 class="font-bold mb-4">Data Penjemput</h3>

                    <input type="text" name="nama_penjemput"
                           placeholder="Nama Penjemput"
                           class="w-full border rounded px-3 py-2 mb-3" required>

                    <input type="text" name="nik"
                           placeholder="NIK"
                           class="w-full border rounded px-3 py-2 mb-3" required>

                    <input type="text" name="hubungan_dengan_anak"
                           placeholder="Hubungan dengan anak"
                           class="w-full border rounded px-3 py-2 mb-3" required>

                    <input type="text" name="alamat"
                           placeholder="Alamat"
                           class="w-full border rounded px-3 py-2 mb-3" required>

                    <input type="text" name="no_hp"
                           placeholder="No HP"
                           class="w-full border rounded px-3 py-2" required>
                </div>

                {{-- PENJEMPUTAN --}}
                <div class="border rounded-lg p-4">
                    <h3 class="font-bold mb-4">Data Penjemputan</h3>

                    <input type="date" name="tanggal_penjemputan"
                           value="{{ date('Y-m-d') }}"
                           class="w-full border rounded px-3 py-2 mb-3" required>

                    {{-- PETUGAS (FIXED) --}}
                    <select name="petugas_id"
                            class="w-full border rounded px-3 py-2 mb-3" required>
                        <option value="">Pilih Petugas</option>
                        @foreach($petugas as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>

                    <textarea name="catatan"
                              placeholder="Catatan"
                              class="w-full border rounded px-3 py-2 mb-3"></textarea>

                    <input type="file" name="bukti_dokumen"
                           class="w-full border rounded px-3 py-2 mb-3">

                    <input type="file" name="berita_acara"
                           class="w-full border rounded px-3 py-2">
                </div>

            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.penjemputan.index') }}"
                   class="bg-gray-300 px-4 py-2 rounded-lg">
                    Batal
                </a>

                <button type="submit"
                        class="bg-green-500 text-white px-4 py-2 rounded-lg">
                    Simpan
                </button>
            </div>

        </form>

    </div>
</div>
@endsection