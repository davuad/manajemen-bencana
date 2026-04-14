@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Data Detail Distribusi</h3>

    <a href="{{ route('detail_distribusi.create') }}" class="btn btn-primary mb-3">Tambah Detail</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Distribusi</th>
                <th>Barang Keluar</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detail_distribusi as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->distribusi->id ?? '-' }}</td>
                <td>{{ $item->barangkeluar->id ?? '-' }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>{{ $item->satuan }}</td>
                <td>{{ $item->keterangan }}</td>
                <td>
                    <form action="{{ route('detail_distribusi.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection