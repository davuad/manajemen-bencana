@extends('layouts.app')

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold">Tambah Data Penerima</h2>
        <p class="text-gray-500 text-sm">
            Lengkapi data penerima distribusi dengan benar
        </p>
    </div>

    <div class="bg-white rounded-xl p-5 m-3 mt-5">
        <form action="/penerima" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Detail Distribusi -->
                <div>
                    <label class="block font-medium">ID Penerima Distribusi *</label>
                    <input type="number" name="detail_distribusi_id"
                        class="w-full border rounded-lg p-3"
                        placeholder="Masukkan ID detail distribusi">
                </div>

                <!-- Nama -->
                <div>
                    <label class="block font-medium">Nama Penerima *</label>
                    <input type="text" name="nama_penerima"
                        class="w-full border rounded-lg p-3"
                        placeholder="Masukkan nama penerima">
                </div>

                <!-- Jabatan -->
                <div>
                    <label class="block font-medium">Jabatan</label>
                    <input type="text" name="jabatan"
                        class="w-full border rounded-lg p-3"
                        placeholder="Contoh: Ketua RT">
                </div>

                <!-- Instansi -->
                <div>
                    <label class="block font-medium">Instansi</label>
                    <input type="text" name="instansi"
                        class="w-full border rounded-lg p-3"
                        placeholder="Contoh: BPBD">
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label class="block font-medium">Alamat</label>
                    <input type="text" name="alamat"
                        class="w-full border rounded-lg p-3"
                        placeholder="Masukkan alamat lengkap">
                </div>

                <!-- No HP -->
                <div>
                    <label class="block font-medium">No HP</label>
                    <input type="text" name="no_hp"
                        class="w-full border rounded-lg p-3"
                        placeholder="08xxxxxxxxxx">
                </div>

                <!-- Posko -->
                <div>
                    <label class="block font-medium">Posko *</label>
                    <select name="nama_posko" class="w-full border rounded-lg p-3">
                        <option value="">Pilih Posko</option>
                        <option value="1">Posko Cilacap Tengah 1</option>
                        <option value="2">Posko Cilacap Selatan 1</option>
                        <option value="3">Posko Cilacap Tengah 2</option>
                        <option value="4">Posko Cilacap Selatan 2</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block font-medium mb-2">Status</label>

                    <div class="flex gap-6">

                        <!-- Belum diterima -->
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="Tidak Aktif"
                                class="accent-red-500 w-4 h-4">
                            <span class="text-red-600 font-medium">
                                Tidak Aktif
                            </span>
                        </label>

                        <!-- Sudah diterima -->
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="Aktif"
                                class="accent-green-500 w-4 h-4">
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

                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
@endsection