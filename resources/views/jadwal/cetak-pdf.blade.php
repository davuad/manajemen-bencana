<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Jadwal Layanan Pasca Bencana</title>
    <style>
        @page { size: A4 landscape; margin: 1cm; }
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 10px; color: #333; background-color: #ffffff; }

        /* ===============
           KOP SURAT 
           =============== */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            /* Menggunakan double border untuk efek garis tebal & tipis khas tata naskah dinas */
            border-bottom: 4px double #000000; 
            margin-bottom: 20px;
        }
        .logo-cell { 
            width: 90px; /* Lebar area logo disesuaikan proporsinya */
            text-align: left; 
            vertical-align: middle;
            padding: 0 0 12px 5px;
            border: none;
        }
        .logo { width: 75px; height: auto; }
        
        .text-cell { 
            text-align: center; 
            vertical-align: middle; 
            padding: 0 0 12px 0; 
            border: none;
        }
        /* Kolom penyeimbang dengan width sama persis seperti logo-cell agar teks murni di tengah */
        .spacer-cell { width: 90px; border: none; }

        /* Tipografi diselaraskan dengan dokumen resmi Kabupaten Cilacap */
        .kop-text h3 { 
            margin: 0; 
            font-size: 14px; 
            font-weight: normal; 
            letter-spacing: 0.5px;
            color: #000000; 
        }
        .kop-text h2 { 
            margin: 4px 0; 
            font-size: 18px; 
            font-weight: bold; 
            text-transform: uppercase; 
            color: #000000; 
            line-height: 1.2;
        }
        /* Teks alamat dibuat tegak lurus (bukan miring/italic) sesuai format baku */
        .kop-text p { 
            margin: 1px 0; 
            font-size: 10px; 
            font-style: normal; 
            color: #000000; 
        }

        /* ==========================================================================
           BAGIAN UTAMA & TABEL DATA
           ========================================================================== */
        /* JUDUL */
        .judul-laporan { text-align: center; margin: 15px 0; }
        .judul-laporan h2 { font-size: 13px; text-decoration: underline; text-transform: uppercase; }

        /* TABEL ISI */
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        table.data-table th { 
            background-color: #facc15; 
            border: 1px solid #000000; 
            padding: 8px; 
            text-transform: uppercase; 
            font-size: 9px; 
            color: #1f2937; 
            font-weight: bold;
        }
        table.data-table td { 
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
                    <p>Jalan Bromo Timur Nomor 13 Sidakaya, Cilacap, Jawa Tengah 53212</p>
                    <p>Telepon (0282) 535093 | Laman: www.cilacapkab.go.id Pos-el: dinsospppa@cilacapkab.go.id</p>
                </div>
            </td>
            <td class="spacer-cell"></td>
        </tr>
    </table>

    <div class="judul-laporan">
        <h2>JADWAL LAYANAN PASCA BENCANA</h2>
    </div>

    <table class="data-table">
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