<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pengambilan Barang</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
        }

        .judul {
            text-align: center;
        }

        .judul h2,
        .judul h3,
        .judul p {
            margin: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .info td {
            padding: 4px;
            vertical-align: top;
        }

        .barang th,
        .barang td {
            border: 1px solid #000;
            padding: 8px;
        }

        .barang th {
            background: #f2f2f2;
            text-align: center;
        }

        .gambar {
            margin-top: 15px;
        }

        .gambar img {
            width: 200px;
            border: 1px solid #000;
        }
    </style>
</head>

<body>

    {{-- Header --}}
   <table class="header">
        <tr>
            {{-- 1. Kolom Kiri untuk Logo --}}
            <td width="15%" align="left">
                @php
                    $logoPath = public_path('logo-dinsos.png');
                    $logoBase64 = '';

                    if (file_exists($logoPath)) {
                        $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                        $logoData = file_get_contents($logoPath);
                        $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($logoData);
                    }
                @endphp

                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" width="80">
                @endif
            </td>

            {{-- 2. Kolom Tengah untuk Judul --}}
            <td width="70%" class="judul">
                <h2>DINAS SOSIAL</h2>
                <h3>KABUPATEN CILACAP</h3>
                <p>LAPORAN PENGAMBILAN BARANG</p>
            </td>

            {{-- 3. Kolom Kanan Kosong (Penyeimbang agar Teks Presisi di Tengah) --}}
            <td width="15%"></td>
        </tr>
    </table>

    {{-- Informasi --}}
    <table class="info">
        <tr>
            <td width="180">Tanggal Pengambilan</td>
            <td>: {{ $data->tanggal_pengambilan }}</td>
        </tr>

        <tr>
            <td>Bencana</td>
            <td>: {{ $data->bencana->nama_bencana ?? '-' }}</td>
        </tr>

        <tr>
            <td>Petugas</td>
            <td>: {{ $data->petugas->nama_petugas ?? '-' }}</td>
        </tr>

        <tr>
            <td>Posko</td>
            <td>: {{ $data->posko->nama_posko ?? '-' }}</td>
        </tr>

        <tr>
            <td>Tujuan</td>
            <td>: {{ $data->tujuan }}</td>
        </tr>

        <tr>
            <td>Status</td>
            <td>: {{ $data->status }}</td>
        </tr>
    </table>

    <br>

    {{-- Barang --}}
    <table class="barang">
        <thead>
            <tr>
                <th width="50">No</th>
                <th>Nama Barang</th>
                <th width="120">Satuan</th>
                <th width="120">Jumlah Diambil</th>
            </tr>
        </thead>

        <tbody>
            @foreach($barangPengambilan as $item)
                <tr>
                    <td align="center">{{ $loop->iteration }}</td>

                    <td>
                        {{ $item->barang->nama_barang ?? '-' }}
                    </td>

                    <td align="center">
                        {{ $item->barang->satuan ?? '-' }}
                    </td>

                    <td align="center">
                        {{ $item->jumlah_ambil }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Dokumentasi --}}
    <div class="gambar">
        <h4>Dokumentasi Pengambilan</h4>

        @php
            $docPath = storage_path('app/public/' . $data->gambar);
            $docBase64 = '';
            if ($data->gambar && file_exists($docPath)) {
                $type = pathinfo($docPath, PATHINFO_EXTENSION);
                $dataDoc = file_get_contents($docPath);
                $docBase64 = 'data:image/' . $type . ';base64,' . base64_encode($dataDoc);
            }
        @endphp

        @if($docBase64)
            <img src="{{ $docBase64 }}">
        @else
            <p>Tidak ada dokumentasi.</p>
        @endif
    </div>

</body>

</html>