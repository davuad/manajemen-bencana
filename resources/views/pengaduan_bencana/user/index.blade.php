@extends('layouts.app')

@section('content')

<div class="container-fluid">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">
        Data Pengaduan Saya
    </h3>

    <a href="{{ route('user.pengaduan.create') }}"
       class="btn btn-primary">
        <i class="fas fa-plus"></i>
        Buat Pengaduan
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>
    </div>
@endif

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">

        <form method="GET"
              action="{{ route('user.pengaduan.index') }}">

            <div class="row">

                <div class="col-md-5 mb-2">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Cari desa atau deskripsi..."
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-4 mb-2">
                    <select name="status"
                            class="form-select">

                        <option value="">
                            Semua Status
                        </option>

                        <option value="BELUM_DITANGANI"
                            {{ request('status') == 'BELUM_DITANGANI' ? 'selected' : '' }}>
                            Belum Ditangani
                        </option>

                        <option value="DITANGANI"
                            {{ request('status') == 'DITANGANI' ? 'selected' : '' }}>
                            Ditangani
                        </option>

                        <option value="SELESAI"
                            {{ request('status') == 'SELESAI' ? 'selected' : '' }}>
                            Selesai
                        </option>

                    </select>
                </div>

                <div class="col-md-3 mb-2">
                    <button class="btn btn-success w-100">
                        Cari
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered align-middle">

                <thead class="table-dark">
                <tr>
                    <th width="60">No</th>
                    <th>Kategori</th>
                    <th>Desa</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th width="120">Aksi</th>
                </tr>
                </thead>

                <tbody>

                @forelse($data as $item)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $item->kategori->nama_kategori ?? '-' }}
                        </td>

                        <td>
                            {{ $item->desa }}
                        </td>

                        <td>
                            {{ Str::limit($item->deskripsi, 50) }}
                        </td>

                        <td>

                            @if($item->status_pengaduan == 'BELUM_DITANGANI')
                                <span class="badge bg-warning text-dark">
                                    Belum Ditangani
                                </span>

                            @elseif($item->status_pengaduan == 'DITANGANI')
                                <span class="badge bg-primary">
                                    Ditangani
                                </span>

                            @elseif($item->status_pengaduan == 'SELESAI')
                                <span class="badge bg-success">
                                    Selesai
                                </span>
                            @endif

                        </td>

                        <td>
                            {{ $item->created_at->format('d-m-Y') }}
                        </td>

                        <td>

                            <a href="{{ route('user.pengaduan.show', $item->id) }}"
                               class="btn btn-info btn-sm">

                                Detail

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7"
                            class="text-center">
                            Belum ada data pengaduan.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>
</div>
</div>
@endsection
