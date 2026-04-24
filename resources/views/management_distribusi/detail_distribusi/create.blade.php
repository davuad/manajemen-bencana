@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Tambah Detail Distribusi</h3>

    <form action="{{ route('detail_distribusi.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Distribusi ID</label>
            <input type="number" name="distribusi_id" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Barang Keluar ID</label>
            <input type="number" name="barang_keluar_id" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Jumlah</label>
            <input type="number" name="jumlah" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Satuan</label>
            <input type="text" name="satuan" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control"></textarea>
        </div>

        <button class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection