@extends('layouts.app')

@section('content')

<div class="mx-3">
    <h2 class="text-xl font-bold">Tambah Barang Masuk</h2>
    <p class="text-gray-500 text-sm">
        Tambahkan data transaksi barang masuk
    </p>
</div>

<div class="bg-white rounded-xl p-5 m-3 mt-5">

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.barang-masuk.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Tanggal -->
            <div>
                <label class="block font-medium">Tanggal Masuk *</label>
                <input type="date"
                       name="tgl_masuk"
                       class="w-full border rounded-lg p-3">
            </div>

            <!-- Sumber -->
            <div>
                <label class="block font-medium">Sumber Barang *</label>
                <select name="id_sumber"
                        class="w-full border rounded-lg p-3">
                    <option value="">Pilih Sumber</option>
                    @foreach($sumber as $s)
                        <option value="{{ $s->id_sumber }}">
                            {{ $s->nama_sumber }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- GUDANG -->
            <div>
                <label class="block font-medium">Gudang *</label>
                <select name="id_gudang" class="w-full border rounded-lg p-3">
                    <option value="">Pilih Gudang</option>
                    @foreach($gudang as $g)
                        <option value="{{ $g->id_gudang }}">
                            {{ $g->nama_gudang }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- No Dokumen -->
            <div>
                <label class="block font-medium">No Dokumen</label>
                <input type="text"
                       name="no_dokumen"
                       class="w-full border rounded-lg p-3"
                       placeholder="Contoh: BA-001">
            </div>

            <!-- Nama Penerima -->
            <div>
                <label class="block font-medium">Penerima *</label>
                <select name="id_pegawai" class="w-full border rounded-lg p-3">
                    <option value="">Pilih Penerima</option>
                    @foreach($pegawai as $p)
                        <option value="{{ $p->id_pegawai }}">
                           {{ $p->nama_pegawai }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div>
                <label class="block font-medium">Status *</label>
                <select name="status" class="w-full border rounded-lg p-3">
                    <option value="diproses">Diproses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>

            <!-- Keterangan -->
            <div class="md:col-span-2">
                <label class="block font-medium">Keterangan</label>
                <textarea name="keterangan"
                          class="w-full border rounded-lg p-3"
                          rows="3"
                          placeholder="Tambahkan catatan..."></textarea>
            </div>

        <div class="md:col-span-2">

            <h3 class="font-semibold mt-6 mb-3 text-lg">Detail Barang</h3>

                <table class="w-full border">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="text-left p-2">Barang</th>
                            <th class="text-left p-2">Jumlah</th>
                            <th class="text-left p-2">Satuan</th>
                            <th class="text-left p-2">Kondisi</th>
                            <th class="text-center p-2">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="detail-body">
                        <tr class="border-t">
                            <!-- Barang -->
                            <td class="p-2">
                                <select name="barang[]" class="w-full border rounded-lg p-1">
                                    <option value="">Pilih Barang</option>
                                    @foreach($barang as $b)
                                        <option value="{{ $b->id_barang }}">
                                            {{ $b->nama_barang }}
                                        </option>
                                    @endforeach
                                </select>      
                            </td>

                            <!-- Jumlah -->
                            <td class="p-2">
                                <input type="number" name="jumlah[]"
                                    class="w-full border rounded-lg p-1">
                            </td>

                            <!-- Satuan -->
                            <td class="p-2">
                                <input type="text" name="satuan[]"
                                    class="w-full border rounded-lg p-1">
                            </td>

                            <!-- Kondisi -->
                            <td class="p-2">
                                <input type="text" name="kondisi[]"
                                    class="w-full border rounded-lg p-1">
                            </td>

                            <!-- Hapus -->
                            <td class="p-2 text-center">
                                <button type="button"
                                    onclick="hapusBaris(this)"
                                    class="text-red-500 hover:text-red-700">
                                    ❌
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

            <button type="button"
                onclick="tambahBaris()"
                    class="mt-3 px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg w-auto">
                + Tambah Barang
            </button>

        <!-- BUTTON -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.barang-masuk.index') }}"
                class="px-4 py-2 bg-gray-300 rounded-lg">
                Batal
            </a>

            <button type="submit"
                    class="px-6 py-2 bg-indigo-700 text-white rounded-lg">
                Simpan Data
            </button>
        </div>

    </form>

</div>

<script>
function tambahBaris() {
    let row = `
    <tr class="border-t">
        <td class="p-2">
            <select name="barang[]" class="w-full border rounded-md p-2">
                <option value="">Pilih Barang</option>
                @foreach($barang as $b)
                    <option value="{{ $b->id_barang }}">
                        {{ $b->nama_barang }}
                    </option>
                @endforeach
            </select>
        </td>

        <td class="p-2">
            <input type="number" name="jumlah[]" class="w-full border rounded-md p-2">
        </td>

        <td class="p-2">
            <input type="text" name="satuan[]" class="w-full border rounded-md p-2">
        </td>

        <td class="p-2">
            <input type="text" name="kondisi[]" class="w-full border rounded-md p-2">
        </td>

        <td class="p-2 text-center">
            <button type="button" onclick="hapusBaris(this)" class="text-red-500">
                ✖
            </button>
        </td>
    </tr>
    `;

    document.getElementById('detail-body')
        .insertAdjacentHTML('beforeend', row);
}

function hapusBaris(btn) {
    btn.closest('tr').remove();
}
</script>

@endsection