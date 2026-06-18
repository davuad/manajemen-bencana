@extends('layouts.app')

@section('content')

<div class="container-fluid">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">
        Form Pengaduan Bencana
    </h3>

    <a href="{{ route('user.pengaduan.index') }}"
       class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Kembali
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Terjadi Kesalahan!</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('user.pengaduan.store') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    {{-- DATA PELAPOR --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                Data Pelapor
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">
                        Nama Pelapor
                    </label>

                    <input type="text"
                           class="form-control"
                           value="{{ Auth::user()->nama }}"
                           readonly>

                </div>

            </div>

        </div>

    </div>

    {{-- DATA PENGADUAN --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                Data Pengaduan
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">
                        Kategori Bencana
                    </label>

                    <select name="kategori_id"
                            class="form-select"
                            required>

                        <option value="">
                            Pilih Kategori
                        </option>

                        @foreach($kategori as $item)
                            <option value="{{ $item->id }}"
                                {{ old('kategori_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_kategori }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">
                        Desa
                    </label>

                    <input type="text"
                           name="desa"
                           class="form-control"
                           value="{{ old('desa') }}"
                           required>

                </div>

            </div>

            <div class="mb-3">

                <label class="form-label fw-bold">
                    Deskripsi Kejadian
                </label>

                <textarea name="deskripsi"
                          rows="6"
                          class="form-control"
                          placeholder="Jelaskan kondisi bencana yang terjadi..."
                          required>{{ old('deskripsi') }}</textarea>

            </div>

        </div>

    </div>

    {{-- DOKUMENTASI --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-info text-white">
            <h5 class="mb-0">
                Dokumentasi Bencana
            </h5>
        </div>

        <div class="card-body">

            <div class="mb-3">

                <label class="form-label fw-bold">
                    Upload Foto
                </label>

                <input type="file"
                       name="foto[]"
                       class="form-control"
                       multiple>

                <small class="text-muted">
                    Dapat memilih lebih dari satu foto.
                </small>

            </div>

            <div class="mb-3">

                <label class="form-label fw-bold">
                    Keterangan Foto
                </label>

                <input type="text"
                       name="keterangan"
                       class="form-control"
                       value="{{ old('keterangan') }}"
                       placeholder="Contoh: Kondisi rumah terdampak banjir">

            </div>

        </div>

    </div>

    {{-- INFORMASI --}}
    <div class="alert alert-warning">

        <strong>Perhatian :</strong>

        <ul class="mb-0 mt-2">
            <li>Pastikan data yang dilaporkan sesuai kondisi sebenarnya.</li>
            <li>Pengaduan akan diverifikasi oleh Kabid.</li>
            <li>Status pengaduan dapat dipantau melalui menu Pengaduan Saya.</li>
        </ul>

    </div>

    <div class="d-flex justify-content-end gap-2">

        <a href="{{ route('user.pengaduan.index') }}"
           class="btn btn-secondary">

            Batal

        </a>

        <button type="submit"
                class="btn btn-primary">

            <i class="fas fa-paper-plane"></i>
            Kirim Pengaduan

        </button>

    </div>

</form>
</div>
@endsection
