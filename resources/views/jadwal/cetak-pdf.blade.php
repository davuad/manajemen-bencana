<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Jadwal Layanan Pasca Bencana</title>
    <style>
        @page { size: A4 landscape; margin: 1cm; }
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 10px; color: #333; background-color: #ffffff; }

        /* KOP SURAT REVISI: Tiga Kolom (Logo | Teks | Spacer) */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px solid #000000;
            margin-bottom: 20px;
        }
        .logo-cell { 
            width: 100px; /* Lebar area logo */
            text-align: left; 
            vertical-align: middle;
            padding: 0 0 10px 10px; /* Logo tidak menempel ke tepi kertas */
            border: none;
        }
        .logo { width: 70px; height: auto; }
        
        .text-cell { 
            text-align: center; 
            vertical-align: middle; 
            padding: 5px; 
            border: none;
        }
        /* Kolom penyeimbang agar teks tetap presisi di tengah */
        .spacer-cell { width: 100px; border: none; }

        .kop-text h3 { margin: 0; font-size: 14px; font-weight: normal; color: #1f2937; }
        .kop-text h2 { margin: 2px 0; font-size: 18px; font-weight: bold; text-transform: uppercase; color: #1f2937; }
        .kop-text p { margin: 1px 0; font-size: 9px; font-style: italic; color: #374151; }

        /* JUDUL */
        .judul-laporan { text-align: center; margin: 15px 0; }
        .judul-laporan h2 { font-size: 13px; text-decoration: underline; text-transform: uppercase; }

        /* TABEL ISI */
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th { 
            background-color: #facc15; 
            border: 1px solid #000000; 
            padding: 8px; 
            text-transform: uppercase; 
            font-size: 9px; 
            color: #1f2937; 
            font-weight: bold;
        }
        td { 
            border: 1px solid #000000; 
            padding: 7px; 
            vertical-align: top; 
            color: #374151; 
            background-color: #ffffff; 
        }

        .text-center { text-align: center; }
        .footer-ttd { margin-top: 25px; width: 100%; }
        .ttd-box { float: right; width: 220px; text-align: center; }
        .space-ttd { height: 60px; }
    </style>
</head>

<body>

    <table class="kop-table">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('logo-dinsos.png') }}" class="logo">
            </td>
            <td class="text-cell">
                <div class="kop-text">
                    <h3>PEMERINTAH KABUPATEN CILACAP</h3>
                    <h2>DINAS SOSIAL, PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK</h2>
                    <p>Jl. Bromo Timur No. 13, Sidakaya, Cilacap, Kode Pos 53212</p>
                    <p>Telp. (0282) 535093 | Email: dinsospppa@cilacapkab.go.id</p>
                </div>
            </td>
            <td class="spacer-cell"></td> </tr>
    </table>

    <div class="judul-laporan">
        <h2>JADWAL LAYANAN PASCA BENCANA</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="22%">Bencana</th>
                <th width="11%">Layanan</th>
                <th width="10%">Target</th>
                <th width="11%">Pegawai</th>
                <th width="11%">Petugas</th>
                <th width="8%">Tanggal</th>
                <th width="9%">Waktu</th>
                <th width="11%">Posko</th>
                <th width="4%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jadwals as $key => $j)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>
                    {{ $j->bencana->nama_bencana ?? 'Bencana' }} - 
                    {{ $j->bencana->desa->nama_desa ?? 'Desa' }} - 
                    {{ $j->bencana ? \Carbon\Carbon::parse($j->bencana->tanggal)->format('Y') : '-' }}
                </td>
                <td>{{ $j->jenis_layanan }}</td>
                <td>{{ $j->sarana }}</td>
                <td>{{ $j->pegawai->nama_pegawai ?? '-' }}</td>
                <td>{{ $j->petugas_lapangan }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($j->tanggal_layanan)->format('d/m/Y') }}</td>
                <td class="text-center">{{ date('H:i', strtotime($j->jam_mulai)) }} - {{ date('H:i', strtotime($j->jam_selesai)) }}</td>
                <td>{{ $j->lokasi_layanan }}</td>
                <td class="text-center">{{ ucfirst($j->status) }}</td>
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