<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Korban</title>
    <style>
        @page {
            margin: 20px 20px 25px 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111827;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 14px;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .header p {
            margin: 4px 0 0;
            font-size: 10px;
            color: #374151;
        }

        .line {
            border-top: 2px solid #000;
            margin-top: 8px;
            margin-bottom: 12px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info-table td {
            border: none;
            padding: 2px 0;
            font-size: 10px;
        }

        .info-label {
            width: 110px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px 5px;
            vertical-align: top;
            word-wrap: break-word;
        }

        th {
            background-color: #e5e7eb;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
        }

        td {
            font-size: 9px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 14px;
            font-size: 10px;
        }

        .signature {
            width: 100%;
            margin-top: 28px;
        }

        .signature td {
            border: none;
            vertical-align: top;
            font-size: 10px;
        }

        .signature-box {
            width: 220px;
            text-align: center;
            float: right;
        }

        .ttd-space {
            height: 55px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN DATA KORBAN BENCANA</h2>
        <p>Sistem Manajemen Bencana</p>
    </div>

    <div class="line"></div>

    <table class="info-table">
        <tr>
            <td class="info-label">Tanggal Cetak</td>
            <td>: {{ now()->format('d-m-Y H:i') }}</td>
        </tr>
        <tr>
            <td class="info-label">Total Data</td>
            <td>: {{ $korban->count() }} korban</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="14%">Nama</th>
                <th width="12%">NIK</th>
                <th width="9%">Jenis Kelamin</th>
                <th width="6%">Umur</th>
                <th width="14%">Bencana</th>
                <th width="12%">Posko</th>
                <th width="15%">Alamat</th>
                <th width="9%">Lokasi Kejadian</th>
                <th width="9%">Tanggal Kejadian</th>
            </tr>
        </thead>
        <tbody>
            @forelse($korban as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->nik ?? '-' }}</td>
                    <td class="text-center">{{ $item->jenis_kelamin }}</td>
                    <td class="text-center">{{ $item->umur }}</td>
                    <td>{{ $item->bencana->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $item->posko->nama_posko ?? '-' }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td>{{ $item->lokasi_kejadian }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($item->tanggal_kejadian)->format('d-m-Y') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">
                        Data korban tidak tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="signature">
        <tr>
            <td></td>
            <td class="signature-box">
                <div>Mengetahui,</div>
                <div>Petugas / Admin</div>
                <div class="ttd-space"></div>
                <div><strong>(................................)</strong></div>
            </td>
        </tr>
    </table>

</body>
</html>