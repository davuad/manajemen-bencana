@extends('layouts.app')

@section('content')

    <div class="bg-white rounded-xl shadow p-6 m-3 mt-5">

        <div class="mb-6 border-b pb-4">
            <h2 class="text-xl font-bold text-gray-800">Edit Jadwal Layanan</h2>
            <p class="text-gray-500 text-sm">
                Perbarui data jadwal layanan untuk penanganan pasca bencana
            </p>
        </div>

        {{-- Error Alert --}}
        @if ($errors->any())
            <div class="mb-6 bg-red-50 text-red-600 p-4 rounded-lg border border-red-100 text-sm">
                <div class="font-bold mb-1">Terjadi kesalahan:</div>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Kolom Kiri --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-gray-700">Bencana *</label>
                        <select name="bencana_id"
                            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">Pilih Kejadian Bencana</option>
                            @foreach ($bencanas as $bencana)
                                <option value="{{ $bencana->id }}"
                                    {{ old('bencana_id') == $bencana->id ? 'selected' : '' }}>
                                    {{-- Format: Nama Bencana - Desa - Tahun --}}
                                    {{ $bencana->nama_bencana }} -
                                    {{ $bencana->desa->nama_desa ?? 'Desa Tidak Ditemukan' }} -
                                    {{ \Carbon\Carbon::parse($bencana->tanggal)->format('Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1 text-gray-700">Pegawai Penanggung Jawab *</label>
                        <select name="pegawai_id"
                            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">Pilih Pegawai</option>
                            @foreach ($pegawais as $p)
                                <option value="{{ $p->id }}"
                                    {{ old('pegawai_id', $jadwal->pegawai_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_pegawai }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1 text-gray-700">Jam Mulai *</label>
                        <input type="time" name="jam_mulai" value="{{ old('jam_mulai', $jadwal->jam_mulai) }}"
                            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1 text-gray-700">Jam Selesai *</label>
                        <input type="time" name="jam_selesai" value="{{ old('jam_selesai', $jadwal->jam_selesai) }}"
                            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-gray-700">Tanggal Layanan *</label>
                        <input type="date" name="tanggal_layanan"
                            value="{{ old('tanggal_layanan', $jadwal->tanggal_layanan) }}"
                            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1 text-gray-700">Jenis Layanan *</label>
                        <input type="text" name="jenis_layanan"
                            value="{{ old('jenis_layanan', $jadwal->jenis_layanan) }}"
                            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 outline-none"
                            placeholder="Contoh: Layanan Kesehatan, Psikolog">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1 text-gray-700">Sasaran *</label>
                        <input type="text" name="sarana" value="{{ old('sarana', $jadwal->sarana) }}"
                            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 outline-none"
                            placeholder="Contoh: Perempuan, Anak-anak">
                    </div>

                    {{-- DROPDOWN STATUS LAYANAN (Update Tugas Baru) --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-gray-700">Status Layanan *</label>
                        <select name="status"
                            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="dijadwalkan"
                                {{ old('status', $jadwal->status) == 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan
                            </option>
                            <option value="selesai" {{ old('status', $jadwal->status) == 'selesai' ? 'selected' : '' }}>
                                Selesai</option>
                        </select>
                    </div>
                </div>

            </div>

            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Petugas Lapangan *</label>
                <input type="text" name="petugas_lapangan"
                    value="{{ old('petugas_lapangan', $jadwal->petugas_lapangan) }}"
                    class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Lokasi Detail Layanan *</label>
                <textarea name="lokasi_layanan" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 outline-none"
                    rows="3">{{ old('lokasi_layanan', $jadwal->lokasi_layanan) }}</textarea>
            </div>

            <div class="flex justify-end gap-3 border-t pt-5">
                <a href="{{ route('admin.jadwal.index') }}"
                    class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-semibold text-sm">
                    Batal
                </a>

                <button type="submit"
                    class="px-8 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-sm font-semibold text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

@endsection
