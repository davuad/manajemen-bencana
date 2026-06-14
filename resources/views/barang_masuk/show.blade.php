@extends('layouts.app')

@section('content')

<div class="bg-white p-6 rounded-xl shadow">

    <h2 class="text-xl font-bold mb-4">Detail Barang Masuk</h2>

    <!-- HEADER -->
    <div class="mb-4 space-y-1">
        <p><b>ID:</b> {{ $data->id_barang_masuk }}</p>
        <p><b>Tanggal:</b> {{ $data->tgl_masuk }}</p>
        <p><b>Sumber:</b> {{ $data->sumber->nama_sumber ?? '-' }}</p>
        <p><b>Gudang:</b> {{ $data->gudang->nama_gudang ?? '-' }}</p>
        <p><b>Penerima:</b> {{ $data->pegawai->nama_pegawai ?? '-' }}</p>
        <p><b>Status:</b> {{ ucfirst($data->status) }}</p>
        <p><b>No Dokumen:</b> {{ $data->no_dokumen }}</p>
        <p><b>Keterangan:</b> {{ $data->keterangan }}</p>
    </div>

    <!-- DETAIL BARANG -->
    <h3 class="font-semibold mt-6 mb-2">Detail Barang</h3>

    <table class="w-full text-sm border">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2">Barang</th>
                <th class="p-2">Jumlah</th>
                <th class="p-2">Satuan</th>
                <th class="p-2">Kondisi</th>
            </tr>
        </thead>

        <tbody>
        @foreach($data->detail as $d)
            <tr class="border-t">
                <td class="p-2">{{ $d->barang->nama_barang ?? '-' }}</td>
                <td class="p-2">{{ $d->jumlah }}</td>
                <td class="p-2">{{ $d->satuan }}</td>
                <td class="p-2">{{ $d->kondisi_barang }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        <a href="/barang-masuk" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Kembali
        </a>
    </div>

</div>

@endsection