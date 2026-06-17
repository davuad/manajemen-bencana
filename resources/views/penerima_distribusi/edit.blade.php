@extends('layouts.app')

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold">Edit Data Penerima</h2>
        <p class="text-gray-500 text-sm">
            Perbarui data penerima distribusi dengan informasi terbaru
        </p>
    </div>

    <div class="bg-white rounded-xl p-5 m-3 mt-5">
       <form action="{{ route('penerima.update', $data->penerima_id) }}" method="POST">
    @csrf
    @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Nama -->
                <div>
                    <label class="block font-medium">Nama Penerima *</label>
                    <input type="text" name="nama_penerima"
                        value="{{ old('nama_penerima', $data->nama_penerima) }}"
                        class="w-full border rounded-lg p-3">
                </div>

                <!-- Jabatan -->
                <div>
                    <label class="block font-medium">Jabatan</label>
                    <input type="text" name="jabatan"
                        value="{{ old('jabatan', $data->jabatan) }}"
                        class="w-full border rounded-lg p-3">
                </div>

                <!-- Instansi -->
                <div>
                    <label class="block font-medium">Instansi</label>
                    <input type="text" name="instansi"
                        value="{{ old('instansi', $data->instansi) }}"
                        class="w-full border rounded-lg p-3">
                </div>

                <!-- No HP -->
                <div>
                    <label class="block font-medium">No HP</label>
                    <input type="text" name="no_hp"
                        value="{{ old('no_hp', $data->no_hp) }}"
                        class="w-full border rounded-lg p-3">
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label class="block font-medium">Alamat</label>
                    <input type="text" name="alamat"
                        value="{{ old('alamat', $data->alamat) }}"
                        class="w-full border rounded-lg p-3">
                </div>

                <!-- Posko -->
                <div>
                    <label class="block font-medium">Posko *</label>
                    <select name="nama_posko" class="w-full border rounded-lg p-3">

                        <option value="">Pilih Posko</option>

                        <option value="1" {{ $data->nama_posko == 1 ? 'selected' : '' }}>
                            Posko CiLacap Tengah 1
                        </option>

                        <option value="2" {{ $data->nama_posko == 2 ? 'selected' : '' }}>
                            Posko Cilacap Selatan 1
                        </option>

                        <option value="3" {{ $data->nama_posko == 3 ? 'selected' : '' }}>
                            Posko Cilacap Tengah 2
                        </option>

                        <option value="4" {{ $data->nama_posko == 4 ? 'selected' : '' }}>
                            Posko Cilacap Selatan 2
                        </option>

                    </select>
                </div>

                <!-- Status (Radio Button Berwarna) -->
                <div class="md:col-span-2">
                    <label class="block font-medium mb-2">Status</label>

                    <div class="flex gap-6">

                        <!-- Belum -->
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="Tidak Aktif"
                                class="accent-red-500 w-4 h-4"
                                {{ $data->status == 'Tidak Aktif' ? 'checked' : '' }}>
                            <span class="text-red-600 font-medium">
                                Tidak Aktif
                            </span>
                        </label>

                        <!-- Sudah -->
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="Aktif"
                                class="accent-green-500 w-4 h-4"
                                {{ $data->status == 'Aktif' ? 'checked' : '' }}>
                            <span class="text-green-600 font-medium">
                                Aktif
                            </span>
                        </label>

                    </div>
                </div>

            </div>

            <!-- Button -->
            <div class="flex justify-end gap-3">
                <a href="/penerima" class="px-4 py-2 bg-gray-300 rounded-lg">
                    Batal
                </a>

                <button type="submit" class="px-6 py-2 bg-yellow-500 text-white rounded-lg">
                    Update Data
                </button>
            </div>
        </form>
    </div> 
@endsection