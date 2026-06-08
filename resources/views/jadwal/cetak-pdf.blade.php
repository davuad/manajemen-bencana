<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Jadwal Layanan Pasca Bencana</title>
    <style>
        @page {
            size: A4 landscape; /* Landscape agar kolom luas */
            margin: 1cm;
        }

        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.3;
        }

        /* KOP SURAT */
        .kop-container {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
            position: relative;
        }
        .logo { position: absolute; left: 0; top: 0; width: 65px; }
        .kop-text h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .kop-text h3 { margin: 0; font-size: 14px; font-weight: normal; }
        .kop-text p { margin: 1px 0; font-size: 9px; }

        /* JUDUL */
        .judul-laporan { text-align: center; margin: 15px 0; }
        .judul-laporan h2 { font-size: 13px; text-decoration: underline; text-transform: uppercase; }

        /* TABEL */
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th { 
            background-color: #f3f4f6; 
            border: 1px solid #d1d5db; 
            padding: 8px; 
            text-transform: uppercase; 
            font-size: 9px; 
            color: #374151; 
        }
        td { border: 1px solid #e5e7eb; padding: 7px; vertical-align: top; }
        tbody tr:nth-child(even) { background-color: #f9fafb; } /* Zebra Striping */

        /* GAYA TEKS */
        .title-cell { font-weight: bold; color: #111827; font-size: 10px; }
        .meta-cell { color: #6b7280; font-size: 8px; text-transform: uppercase; margin-top: 2px; }
        .text-center { text-align: center; }

        /* FOOTER TTD */
        .footer-ttd { margin-top: 25px; width: 100%; }
        .ttd-box { float: right; width: 220px; text-align: center; }
        .space-ttd { height: 60px; }
    </style>
</head>

<body>

    <div class="kop-container">
        <img src="{{ public_path('logo-dinsos.png') }}" class="logo">
        <div class="kop-text">
            <h3>PEMERINTAH KABUPATEN CILACAP</h3>
            <h2>DINAS SOSIAL, PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK</h2>
            <p>Jl. Bromo Timur No. 13, Sidakaya, Cilacap, Kode Pos 53212</p>
            <p>Telp. (0282) 535093 | Email: dinsospppa@cilacapkab.go.id</p>
        </div>
    </div>

    <div class="judul-laporan">
        <h2>JADWAL LAYANAN PASCA BENCANA</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="18%">Bencana</th>
                <th width="12%">Layanan</th>
                <th width="10%">Target</th>
                <th width="12%">Pegawai</th>
                <th width="12%">Petugas</th>
                <th width="9%">Tanggal</th>
                <th width="10%">Waktu</th>
                <th width="10%">Posko</th>
                <th width="4%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jadwals as $key => $j)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>
                    <div class="title-cell">{{ $j->bencana->nama_bencana ?? '-' }}</div>
                    <div class="meta-cell">{{ $j->bencana->desa->nama_desa ?? '-' }} • {{ $j->bencana ? \Carbon\Carbon::parse($j->bencana->tanggal)->format('Y') : '-' }}</div>
                </td>
                <td>{{ $j->jenis_layanan }}</td>
                <td>{{ $j->sarana }}</td>
                <td>{{ $j->pegawai->nama_pegawai ?? '-' }}</td>
                <td>{{ $j->petugas_lapangan }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($j->tanggal_layanan)->format('d/m/Y') }}</td>
                <td class="text-center">{{ date('H:i', strtotime($j->jam_mulai)) }} - {{ date('H:i', strtotime($j->jam_selesai)) }}</td>
                <td>{{ $j->lokasi_layanan }}</td>
                <td class="text-center" style="font-weight: bold;">{{ strtoupper($j->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-ttd">
        <div class="ttd-box">
            <p>Cilacap, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Kepala Dinas,</p>
            <div class="space-ttd"></div>
            <p style="font-weight: bold;">(..........................................)</p>
        </div>
    </div>

</body>
</html>