@extends('layouts.app')

@section('title', 'Edit Data Warga Terdampak')

@section('content')
    <div class="space-y-6">
        {{-- Breadcrumb --}}
        <div class="text-sm text-gray-500">
            Dashboard <span class="mx-1">&gt;</span> Data Warga Terdampak <span class="mx-1">&gt;</span> Edit Data Warga
            Terdampak
        </div>

        {{-- Card --}}
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-5">
                <h2 class="text-xl font-bold text-gray-900">
                    Edit Data Warga Terdampak
                </h2>
            </div>

            <div class="px-6 py-6 md:px-8 md:py-8">
                <form action="{{ route('admin.warga.update', $warga->id) }}" method="POST" class="space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 md:gap-x-8 md:gap-y-7">
                        {{-- No KK --}}
                        <div>
                            <label for="no_kk" class="mb-2 block text-sm font-medium text-gray-700">
                                Nomor Kartu Keluarga
                            </label>
                            <input type="text" id="no_kk" name="no_kk" value="{{ old('no_kk', $warga->no_kk) }}"
                                placeholder="Masukkan nomor kartu keluarga"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100">
                            @error('no_kk')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jumlah Anggota --}}
                        <div>
                            <label for="jumlah_anggota" class="mb-2 block text-sm font-medium text-gray-700">
                                Jumlah Anggota
                            </label>
                            <input type="number" id="jumlah_anggota" name="jumlah_anggota" min="1"
                                value="{{ old('jumlah_anggota', $warga->jumlah_anggota) }}"
                                placeholder="Masukkan jumlah anggota"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100">
                            @error('jumlah_anggota')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NIK Kepala Keluarga --}}
                        <div>
                            <label for="nik_kepala_keluarga" class="mb-2 block text-sm font-medium text-gray-700">
                                NIK Kepala Keluarga
                            </label>
                            <input type="text" id="nik_kepala_keluarga" name="nik_kepala_keluarga"
                                value="{{ old('nik_kepala_keluarga', $warga->nik_kepala_keluarga) }}"
                                placeholder="Masukkan NIK kepala keluarga"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100">
                            @error('nik_kepala_keluarga')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal Pendataan --}}
                        <div>
                            <label for="tanggal_pendataan" class="mb-2 block text-sm font-medium text-gray-700">
                                Tanggal Pendataan
                            </label>
                            <input type="date" id="tanggal_pendataan" name="tanggal_pendataan"
                                value="{{ old('tanggal_pendataan', $warga->tanggal_pendataan ? \Carbon\Carbon::parse($warga->tanggal_pendataan)->format('Y-m-d') : '') }}"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100">
                            @error('tanggal_pendataan')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nama Kepala Keluarga --}}
                        <div>
                            <label for="nama_kepala_keluarga" class="mb-2 block text-sm font-medium text-gray-700">
                                Nama Kepala Keluarga
                            </label>
                            <input type="text" id="nama_kepala_keluarga" name="nama_kepala_keluarga"
                                value="{{ old('nama_kepala_keluarga', $warga->nama_kepala_keluarga) }}"
                                placeholder="Masukkan nama kepala keluarga"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100">
                            @error('nama_kepala_keluarga')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jenis Bantuan --}}
                        <div>
                            <label for="jenis_bantuan" class="mb-2 block text-sm font-medium text-gray-700">
                                Jenis Bantuan
                            </label>
                            <select id="jenis_bantuan" name="jenis_bantuan"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100">
                                <option value="">Pilih jenis bantuan</option>
                                <option value="Bantuan Saat Bencana"
                                    {{ old('jenis_bantuan', $warga->jenis_bantuan) == 'Bantuan Saat Bencana' ? 'selected' : '' }}>
                                    Bantuan Saat Bencana
                                </option>
                                <option value="Bantuan Pasca Bencana"
                                    {{ old('jenis_bantuan', $warga->jenis_bantuan) == 'Bantuan Pasca Bencana' ? 'selected' : '' }}>
                                    Bantuan Pasca Bencana
                                </option>
                            </select>
                            @error('jenis_bantuan')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Alamat --}}
                        <div>
                            <label for="alamat" class="mb-2 block text-sm font-medium text-gray-700">
                                Alamat
                            </label>
                            <textarea id="alamat" name="alamat" rows="4" placeholder="Masukkan alamat terdampak"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100">{{ old('alamat', $warga->alamat) }}</textarea>
                            @error('alamat')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status Penyaluran --}}
                        <div>
                            <label for="status_penyaluran" class="mb-2 block text-sm font-medium text-gray-700">
                                Status Penyaluran
                            </label>
                            <select id="status_penyaluran" name="status_penyaluran"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100">
                                <option value="">Pilih status penyaluran</option>
                                <option value="Belum diproses"
                                    {{ old('status_penyaluran', $warga->status_penyaluran) == 'Belum diproses' ? 'selected' : '' }}>
                                    Belum Diproses
                                </option>
                                <option value="Proses Penyaluran"
                                    {{ old('status_penyaluran', $warga->status_penyaluran) == 'Proses Penyaluran' ? 'selected' : '' }}>
                                    Proses Penyaluran
                                </option>
                                <option value="Sudah disalurkan"
                                    {{ old('status_penyaluran', $warga->status_penyaluran) == 'Sudah disalurkan' ? 'selected' : '' }}>
                                    Sudah Disalurkan
                                </option>
                            </select>
                            @error('status_penyaluran')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Desa --}}
                        <div>
                            <label for="desa_id" class="mb-2 block text-sm font-medium text-gray-700">
                                Desa
                            </label>
                            <select id="desa_id" name="desa_id"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100">
                                <option value="">Pilih desa</option>
                                @foreach ($desa as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('desa_id', $warga->desa_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_desa }}
                                    </option>
                                @endforeach
                            </select>
                            @error('desa_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal Penyaluran --}}
                        <div>
                            <label for="tanggal_penyaluran" class="mb-2 block text-sm font-medium text-gray-700">
                                Tanggal Penyaluran
                            </label>
                            <input type="date" id="tanggal_penyaluran" name="tanggal_penyaluran"
                                value="{{ old('tanggal_penyaluran', $warga->tanggal_penyaluran ? \Carbon\Carbon::parse($warga->tanggal_penyaluran)->format('Y-m-d') : '') }}"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-400 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100">
                            @error('tanggal_penyaluran')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Bencana (Diperbarui dari Kategori) --}}
                        <div>
                            <label for="bencana_id" class="mb-2 block text-sm font-medium text-gray-700">
                                Bencana
                            </label>
                            <select id="bencana_id" name="bencana_id"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100">
                                <option value="">Pilih bencana</option>
                                @foreach ($bencana as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('bencana_id', $warga->bencana_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_bencana }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bencana_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:justify-end">
                        <a href="{{ route('admin.warga.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                            Batal
                        </a>

                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-indigo-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-800">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleTanggalPenyaluran() {
            const status = document.getElementById('status_penyaluran').value;
            const tanggal = document.getElementById('tanggal_penyaluran');

            if (status === 'Belum diproses') {
                tanggal.value = '';
                tanggal.setAttribute('disabled', 'disabled');
            } else {
                tanggal.removeAttribute('disabled');
            }
        }

        document.getElementById('status_penyaluran').addEventListener('change', toggleTanggalPenyaluran);

        window.addEventListener('load', function() {
            toggleTanggalPenyaluran();
        });
    </script>
@endsection
