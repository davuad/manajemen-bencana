@extends('layouts.app')

@section('content')

<div class="mx-3">
    <h2 class="text-xl font-bold">Edit Barang Masuk</h2>
    <p class="text-gray-500 text-sm">
        Perbarui data transaksi barang masuk
    </p>
</div>

<div class="bg-white rounded-xl p-5 m-3 mt-5">

    <form action="{{ route('admin.barang-masuk.update', $data->id_barang_masuk) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- ID -->
            <div>
                <label class="block font-medium">ID Barang Masuk</label>
                <input type="text"
                       value="{{ $data->id_barang_masuk }}"
                       class="w-full border rounded-lg p-3 bg-gray-100"
                       readonly>
            </div>

            <!-- Tanggal -->
            <div>
                <label class="block font-medium">Tanggal Masuk *</label>
                <input type="date"
                       name="tgl_masuk"
                       value="{{ $data->tgl_masuk }}"
                       class="w-full border rounded-lg p-3">
            </div>

            <!-- Sumber -->
            <div>
                <label class="block font-medium">Sumber Barang *</label>
                <select name="id_sumber" class="w-full border rounded-lg p-3">
                    @foreach($sumber as $s)
                        <option value="{{ $s->id_sumber }}"
                            {{ $data->id_sumber == $s->id_sumber ? 'selected' : '' }}>
                            {{ $s->nama_sumber }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Gudang -->
            <div>
                <label class="block font-medium">Gudang *</label>
                <select name="id_gudang" class="w-full border rounded-lg p-3">
                    @foreach($gudang as $g)
                        <option value="{{ $g->id_gudang }}"
                            {{ $data->id_gudang == $g->id_gudang ? 'selected' : '' }}>
                            {{ $g->nama_gudang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Penerima (Pegawai) -->
            <div>
                <label class="block font-medium">Penerima *</label>
                <select name="id_pegawai" class="w-full border rounded-lg p-3">
                    @foreach($pegawai as $p)
                        <option value="{{ $p->id_pegawai }}"
                            {{ $data->id_pegawai == $p->id_pegawai ? 'selected' : '' }}>
                            {{ $p->nama_pegawai }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div>
                <label class="block font-medium">Status *</label>
                <select name="status" class="w-full border rounded-lg p-3">
                    <option value="diproses" {{ $data->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ $data->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <!-- No Dokumen -->
            <div>
                <label class="block font-medium">No Dokumen</label>
                <input type="text"
                       name="no_dokumen"
                       value="{{ $data->no_dokumen }}"
                       class="w-full border rounded-lg p-3">
            </div>

            <!-- Keterangan -->
            <div class="md:col-span-2">
                <label class="block font-medium">Keterangan</label>
                <textarea name="keterangan"
                          class="w-full border rounded-lg p-3"
                          rows="3">{{ $data->keterangan }}</textarea>
            </div>

        </div>

        <!-- DETAIL BARANG -->
        <div class="mt-6">
            <h3 class="font-semibold mb-3">Detail Barang</h3>

            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2">Barang</th>
                        <th class="p-2">Jumlah</th>
                        <th class="p-2">Satuan</th>
                        <th class="p-2">Kondisi</th>
                        <th class="p-2">Aksi</th>
                    </tr>
                </thead>

            <tbody>
                @foreach($data->detail as $i => $detail)
                <tr>

                    <td class="border p-2">

                        <input type="hidden"
                                name="detail_id[]"
                                value="{{ $detail->id_detail_barang_masuk }}">

                        <select name="barang[]" class="w-full border rounded p-1">

                            @foreach($barang as $b)
                                <option value="{{ $b->id_barang }}"
                                    {{ $detail->id_barang == $b->id_barang ? 'selected' : '' }}>
                                    {{ $b->nama_barang }}
                                </option>
                            @endforeach

                        </select>

                    </td>

                    <td class="border p-2">
                        <input type="number"
                                name="jumlah[]"
                                value="{{ $detail->jumlah }}"
                                class="w-full border rounded p-1">
                    </td>

                    <td class="border p-2">
                        <input type="text"
                                name="satuan[]"
                                value="{{ $detail->satuan }}"
                                class="w-full border rounded p-1">
                    </td>

                    <td class="border p-2">
                        <input type="text"
                                name="kondisi[]"
                                value="{{ $detail->kondisi_barang }}"
                                class="w-full border rounded p-1">
                    </td>

                    <td class="border p-2 text-center">
                        <button type="button"
                                onclick="hapusBaris(this)"
                                class="text-red-500">
                            ❌
                        </button>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <button type="button"
            onclick="tambahBaris()"
            class="mt-3 bg-indigo-100 text-indigo-700 px-4 py-2 rounded-lg">
        + Tambah Barang
    </button>

        <!-- BUTTON -->
        <div class="flex justify-end gap-3">
            <a href="/barang-masuk"
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

<script>

function tambahBaris() {

    let row = `
    <tr>

        <td class="border p-2">

            <input type="hidden" name="detail_id[]" value="">

            <select name="barang[]" class="w-full border rounded p-1">

                @foreach($barang as $b)
                    <option value="{{ $b->id_barang }}">
                        {{ $b->nama_barang }}
                    </option>
                @endforeach

            </select>

        </td>

        <td class="border p-2">
            <input type="number"
                   name="jumlah[]"
                   class="w-full border rounded p-1">
        </td>

        <td class="border p-2">
            <input type="text"
                   name="satuan[]"
                   class="w-full border rounded p-1">
        </td>

        <td class="border p-2">
            <input type="text"
                   name="kondisi[]"
                   class="w-full border rounded p-1">
        </td>

        <td class="border p-2 text-center">
            <button type="button"
                    onclick="hapusBaris(this)"
                    class="text-red-500">
                ❌
            </button>
        </td>

    </tr>
    `;

    document.querySelector('tbody')
        .insertAdjacentHTML('beforeend', row);
}

function hapusBaris(btn) {
    btn.closest('tr').remove();
}

</script>

@endsection