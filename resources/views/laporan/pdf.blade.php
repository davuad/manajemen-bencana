<!DOCTYPE html>
<html>

<head>
    <title>Laporan Data Bencana</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .kop {
            text-align: center;
            line-height: 1.3;
        }

        /* HILANGKAN jarak bawaan heading */
        .kop h3,
        .kop h4,
        .kop p {
            margin: 2px 0;
        }

        .logo {
            position: absolute;
            left: 30px;
            top: 10px;
            width: 60px;
            /* 🔥 atur ukuran di sini */
        }

        .judul {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        hr {
            border: 2px solid black;
            margin-top: 8px;
            /* 🔥 ini yang diperbaiki */
            margin-bottom: 10px;
        }

        .filter {
            margin-top: 10px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 6px;
            text-align: center;
        }

        th {
            font-weight: bold;
        }

        .ttd {
            width: 100%;
            margin-top: 50px;
        }

        .ttd-kanan {
            width: 250px;
            float: right;
            text-align: center;
        }
    </style>

</head>

<body>

    <!-- LOGO -->
    <img src="{{ public_path('logo-dinsos.png') }}" width="80" class="logo">

    <!-- KOP SURAT -->
    <div class="kop">
        <h3>PEMERINTAH KABUPATEN CILACAP</h3>
        <h4>DINAS SOSIAL</h4>
        <p>Jl. Bromo Timur No. 13, Sidakaya, Cilacap</p>
        <p>Telp. (0282) 535093 | Kode Pos 53212</p>
    </div>

    <hr>

    <!-- JUDUL -->
    <div class="judul">
        <h3>LAPORAN DATA BENCANA</h3>
        <h4>KABUPATEN CILACAP</h4>
    </div>

    <!-- FILTER -->
    <div class="filter">

        @if ($tanggal_mulai && $tanggal_selesai)
            <p>
                Periode :
                {{ \Carbon\Carbon::parse($tanggal_mulai)->format('d-m-Y') }}
                s/d
                {{ \Carbon\Carbon::parse($tanggal_selesai)->format('d-m-Y') }}
            </p>
        @endif

        @if ($search)
            <p>Nama Desa : {{ $search }}</p>
        @endif

        @if ($status)
            <p>Status : {{ $status }}</p>
        @endif

        @if (!$tanggal_mulai && !$tanggal_selesai && !$search && !$status)
            <p>Menampilkan seluruh data</p>
        @endif

    </div>

    <!-- TABEL -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Desa</th>
                <th>Tingkat Kerusakan</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($laporan as $index => $b)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($b->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $b->desa->nama_desa ?? '-' }}</td>
                    <td>{{ $b->tingkat_kerusakan }}</td>
                    <td>{{ $b->pengaduan->status_pengaduan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <div class="ttd">
        <div class="ttd-kanan">
            <p>Cilacap, {{ date('d-m-Y') }}</p>
            <p>Kepala Dinas Sosial</p>

            <br><br><br>

            <p><b>(________________________)</b></p>
        </div>
    </div>

</body>

</html>
