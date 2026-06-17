@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">
            Detail Pengaduan
        </h3>

        <a href="{{ route('user.pengaduan.index') }}"
           class="btn btn-secondary">
            Kembali
        </a>
    </div>

    {{-- STATUS --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <h5 class="mb-3">Status Pengaduan</h5>

            @if($data->status_pengaduan == 'BELUM_DITANGANI')
                <span class="badge bg-warning text-dark fs-6">
                    Belum Ditangani
                </span>

            @elseif($data->status_pengaduan == 'DITANGANI')
                <span class="badge bg-primary fs-6">
                    Sedang Ditangani
                </span>

            @elseif($data->status_pengaduan == 'SELESAI')
                <span class="badge bg-success fs-6">
                    Selesai
                </span>
            @endif

        </div>
    </div>

    {{-- DATA PENGADUAN --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
            Data Pengaduan
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th width="250">Nama Pelapor</th>
                    <td>{{ $data->user->nama ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Kategori Bencana</th>
                    <td>{{ $data->kategori->nama_kategori ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Desa</th>
                    <td>{{ $data->desa }}</td>
                </tr>

                <tr>
                    <th>Deskripsi</th>
                    <td>{{ $data->deskripsi }}</td>
                </tr>

                <tr>
                    <th>Tanggal Pengaduan</th>
                    <td>{{ $data->created_at->format('d-m-Y H:i') }}</td>
                </tr>

                @if($data->tanggal_selesai)
                <tr>
                    <th>Tanggal Selesai</th>
                    <td>{{ \Carbon\Carbon::parse($data->tanggal_selesai)->format('d-m-Y') }}</td>
                </tr>
                @endif

            </table>

        </div>
    </div>

    {{-- KEBUTUHAN --}}
    @if($data->kebutuhan)
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-success text-white">
            Kebutuhan Bantuan
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th width="250">Dapur Umum</th>
                    <td>{{ $data->kebutuhan->dapur_umum }}</td>
                </tr>

                <tr>
                    <th>Psikososial</th>
                    <td>{{ $data->kebutuhan->psikososial }}</td>
                </tr>

                <tr>
                    <th>Logistik Rentan</th>
                    <td>{{ $data->kebutuhan->logistik_rentan }}</td>
                </tr>

                <tr>
                    <th>Logistik Makanan</th>
                    <td>{{ $data->kebutuhan->logistik_makanan }}</td>
                </tr>

                <tr>
                    <th>Logistik Penampungan</th>
                    <td>{{ $data->kebutuhan->logistik_penampungan }}</td>
                </tr>

                <tr>
                    <th>Keterangan</th>
                    <td>{{ $data->kebutuhan->keterangan }}</td>
                </tr>

            </table>

        </div>

    </div>
    @endif

    {{-- FOTO --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-info text-white">
            Dokumentasi Foto
        </div>

        <div class="card-body">

            <div class="row">

                @forelse($data->foto as $foto)

                    <div class="col-md-4 mb-3">

                        <div class="card">

                            <img src="{{ asset('foto/' . $foto->file_foto) }}"
                                 class="card-img-top"
                                 style="height:250px; object-fit:cover;">

                            <div class="card-body">

                                <small class="text-muted">
                                    {{ $foto->keterangan }}
                                </small>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="col-12">
                        <div class="alert alert-warning">
                            Tidak ada foto yang diupload.
                        </div>
                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>
@endsection