<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Berita Acara</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 40px;
        }

        .header {
            text-align: center;
            position: relative;
        }

        .header img {
            width: 70px;
            position: absolute;
            left: 0;
            top: 0;
        }

        .header h3 {
            margin: 0;
        }

        .header p {
            font-size: 11px;
            margin: 2px 0;
        }

        hr {
            border: 1px solid black;
            margin-top: 10px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
            text-decoration: underline;
        }

        .content {
            margin-top: 20px;
            line-height: 1.6;
            text-align: justify;
        }

        .data-table {
            margin-top: 10px;
        }

        .data-table td {
            padding: 2px 5px;
        }

        .barang-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .barang-table th, .barang-table td {
            border: 1px solid #999;
            padding: 6px;
            text-align: center;
        }

        .barang-table th {
            background: #eee;
        }

        .ttd {
            margin-top: 50px;
            width: 100%;
        }

        .ttd td {
            text-align: center;
            padding-top: 30px;
        }

        .footer {
            margin-top: 50px;
            font-size: 10px;
            color: gray;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <img src="{{ public_path('logo-dinsos.png') }}">
    <h3>PEMERINTAH KABUPATEN CILACAP</h3>
    <h3>DINAS SOSIAL</h3>
    <p>Jl. Jenderal Sudirman No.123, Cilacap</p>
    <p>Telp: (0282) 531423 | www.dinsos.cilacapkab.go.id</p>
    <hr>
</div>

<!-- TITLE -->
<div class="title">
    BERITA ACARA SERAH TERIMA DISTRIBUSI BARANG UNTUK KORBAN BENCANA
</div>

<!-- CONTENT -->
<div class="content">

    <p>
        Pada hari ini tanggal <b>{{ $data->tanggal_distribusi }}</b>, bertempat di 
        <b>{{ $data->lokasi_distribusi }}</b>, telah dilakukan kegiatan distribusi bantuan logistik
        untuk bencana <b>{{ $data->nama_bencana }}</b>.
    </p>

    <table class="data-table">
        <tr><td>Posko</td><td>: {{ $data->nama_posko }}</td></tr>
        <tr><td>Kendaraan</td><td>: {{ $data->kendaraan }}</td></tr>
        <tr><td>Supir</td><td>: {{ $data->nama_supir }}</td></tr>
        <tr><td>No Kendaraan</td><td>: {{ $data->nomor_kendaraan }}</td></tr>
        <tr><td>Kategori</td><td>: {{ $data->kategori_distribusi }}</td></tr>
        <tr><td>Status</td><td>: {{ ucfirst($data->status) }}</td></tr>
    </table>

    <p>
        Berikut rincian barang yang didistribusikan:
    </p>

    <!-- TABLE BARANG (STATIC / NANTI BISA DINAMIS) -->
    <table class="barang-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Beras Premium</td>
                <td>Karung</td>
                <td>50</td>
                <td>-</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Mie Instan</td>
                <td>Karton</td>
                <td>25</td>
                <td>-</td>
            </tr>
        </tbody>
    </table>

    <p style="margin-top:15px;">
        Demikian berita acara ini dibuat dengan sebenarnya untuk digunakan sebagaimana mestinya.
    </p>

</div>

<!-- TTD -->
<table class="ttd">
    <tr>
        <td>
            Yang Menerima,<br>
            <b>PIHAK KEDUA</b><br><br><br><br>
            ______________________
        </td>
        <td>
            Yang Menyerahkan,<br>
            <b>PIHAK PERTAMA</b><br><br><br><br>
            {{ $data->nama_supir }}
        </td>
    </tr>
</table>

<!-- FOOTER -->
<div class="footer">
    <span>DOKUMEN DIGITAL DINSOS CILACAP</span>
    <span>HALAMAN 1</span>
</div>

</body>
</html>