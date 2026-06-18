@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <h2 class="text-2xl font-bold mb-4">Detail Barang Masuk</h2>

    <p class="mb-1"><strong>ID:</strong> {{ $data->id }}</p>
    <p class="mb-1"><strong>Tanggal:</strong> {{ $data->tanggal }}</p>
    <p class="mb-1"><strong>Sumber:</strong> {{ $data->sumber }}</p>
    <p class="mb-1"><strong>Gudang:</strong> {{ $data->gudang->nama ?? '-' }}</p>
    <p class="mb-1"><strong>Penerima:</strong> {{ $data->penerima }}</p>
    <p class="mb-1"><strong>Status:</strong> {{ $data->status }}</p>
    <p class="mb-1"><strong>No Dokumen:</strong> {{ $data->no_dokumen }}</p>
    <p class="mb-1"><strong>Keterangan:</strong> {{ $data->keterangan }}</p>

    <h3 class="text-lg font-semibold mt-6 mb-2">Detail Barang</h3>

    <table class="w-full border border-gray-300">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 text-center">Barang</th>
                <th class="p-2 text-center">Jumlah</th>
                <th class="p-2 text-center">Satuan</th>
                <th class="p-2 text-center">Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->detail as $item)
            <tr class="border-t">
                <td class="p-2 text-center">
                    {{ $item->barang->nama_barang ?? '-' }}
                </td>
                <td class="p-2 text-center">{{ $item->jumlah }}</td>
                <td class="p-2 text-center">{{ $item->satuan }}</td>
                <td class="p-2 text-center">
                    @if($item->kondisi == 'rusak')
                        <span class="text-red-500 font-semibold">Rusak</span>
                    @else
                        <span class="text-green-500 font-semibold">Baik</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="/barang-masuk"
        style="background-color: red; color: white; padding: 10px;">
        TEST
    </a>

</div>

@endsection