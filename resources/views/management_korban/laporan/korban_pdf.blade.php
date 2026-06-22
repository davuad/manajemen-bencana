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
            margin-bottom: 12px;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }

        .header p {
            margin: 3px 0 0;
            font-size: 10px;
            color: #374151;
        }

        .line {
            border-top: 2px solid #000;
            margin: 8px 0 12px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .info-table td {
            border: none;
            padding: 2px 0;
            font-size: 10px;
        }

        .info-label {
            width: 120px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: top;
            word-wrap: break-word;
        }

        th {
            background: #e5e7eb;
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

        .signature {
            width: 100%;
            margin-top: 25px;
        }

        .signature td {
            border: none;
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

    @php
        $hideBencana = !empty($bencana);
        $hidePosko = !empty($posko);

        $jumlahKolom = 8;

        if (!$hideBencana) {
            $jumlahKolom++;
        }

        if (!$hidePosko) {
            $jumlahKolom++;
        }
    @endphp

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

        @if($bencana)
            <tr>
                <td class="info-label">Bencana</td>
                <td>
                    :
                    {{ $bencana->kategori->nama_kategori ?? '-' }}
                    -
                    {{ $bencana->desa->nama_desa ?? '-' }}
                    -
                    {{ \Carbon\Carbon::parse($bencana->tanggal)->format('d-m-Y') }}
                </td>
            </tr>
        @endif

        @if($posko)
            <tr>
                <td class="info-label">Posko</td>
                <td>: {{ $posko->nama_posko }}</td>
            </tr>
        @endif
    </table>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="14%">Nama</th>
                <th width="11%">NIK</th>
                <th width="8%">Jenis Kelamin</th>
                <th width="5%">Umur</th>

                @if(!$hideBencana)
                    <th width="15%">Bencana</th>
                @endif

                @if(!$hidePosko)
                    <th width="10%">Posko</th>
                @endif

                <th width="14%">Alamat</th>
                <th width="10%">Lokasi Kejadian</th>
                <th width="9%">Tanggal Kejadian</th>
            </tr>
        </thead>

        <tbody>

            @forelse($korban as $index => $item)

                <tr>

                    <td class="text-center">
                        {{ $index + 1 }}
                    </td>

                    <td>
                        {{ $item->nama }}
                    </td>

                    <td>
                        {{ $item->nik ?? '-' }}
                    </td>

                    <td class="text-center">
                        {{ $item->jenis_kelamin }}
                    </td>

                    <td class="text-center">
                        {{ $item->umur }}
                    </td>

                    @if(!$hideBencana)
                        <td>
                            {{ $item->bencana->kategori->nama_kategori ?? '-' }}
                            -
                            {{ $item->bencana->desa->nama_desa ?? '-' }}
                            -
                            {{ \Carbon\Carbon::parse($item->bencana->tanggal)->format('d-m-Y') }}
                        </td>
                    @endif

                    @if(!$hidePosko)
                        <td>
                            {{ $item->posko->nama_posko ?? '-' }}
                        </td>
                    @endif

                    <td>
                        {{ $item->alamat }}
                    </td>

                    <td>
                        {{ $item->lokasi_kejadian }}
                    </td>

                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($item->tanggal_kejadian)->format('d-m-Y') }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="{{ $jumlahKolom }}" class="text-center">
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

                <div>
                    <strong>(................................)</strong>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>