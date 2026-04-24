@extends('layouts.app')

@section('content')
<div class="p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Detail Distribusi</h2>

        <!-- Tombol Kembali -->
        <a href="{{ route('management_distribusi.distribusi.index') }}"
           class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
            ← Kembali
        </a>
    </div>

    <!-- Card Detail -->
    <div class="bg-white p-5 rounded shadow space-y-2">

        <p><b>ID:</b> {{ $distribusi->id }}</p>
        <p><b>Tanggal:</b> {{ $distribusi->tanggal_distribusi }}</p>
        <p><b>Lokasi:</b> {{ $distribusi->lokasi_distribusi }}</p>
        <p><b>Kendaraan:</b> {{ $distribusi->kendaraan }}</p>
        <p><b>Supir:</b> {{ $distribusi->nama_supir }}</p>
        <p><b>No Kendaraan:</b> {{ $distribusi->nomor_kendaraan }}</p>

        <!-- Status -->
        <p>
            <b>Status:</b>
            <span class="px-2 py-1 text-xs rounded
                {{ $distribusi->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                {{ $distribusi->status == 'dikirim' ? 'bg-blue-100 text-blue-700' : '' }}
                {{ $distribusi->status == 'selesai' ? 'bg-green-100 text-green-700' : '' }}">
                {{ ucfirst($distribusi->status) }}
            </span>
        </p>

    </div>

    <!-- Detail Barang -->
    <h3 class="mt-6 font-bold">Detail Barang</h3>

    <div class="bg-white rounded shadow mt-2">
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">Barang Keluar</th>
                    <th class="p-2">Jumlah</th>
                    <th class="p-2">Satuan</th>
                </tr>
            </thead>

            <tbody>
                @forelse($distribusi->detailDistribusis as $d)
                <tr class="border-t">
                    <td class="p-2">{{ $d->barang_keluar_id }}</td>
                    <td class="p-2">{{ $d->jumlah }}</td>
                    <td class="p-2">{{ $d->satuan }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center p-3 text-gray-500">
                        Tidak ada detail barang
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection