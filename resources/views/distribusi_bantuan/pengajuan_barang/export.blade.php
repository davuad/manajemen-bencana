{{-- resources/views/distribusi_bantuan/pengajuan_barang/export.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Rinci Pengajuan Logistik - Politeknik Negeri Cilacap</title>
    <style>
        @page { size: A4 landscape; margin: 1cm; }
        body { font-family: 'Arial', sans-serif; font-size: 8.5pt; color: #333; line-height: 1.4; }
        
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; }
        .header h1 { margin: 0; font-size: 14pt; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 10pt; }

        /* Kotak Informasi Filter */
        .filter-info { 
            margin: 10px auto 20px auto; 
            font-size: 8pt; 
            display: table; 
            text-align: left; 
            border: 1px solid #ddd; 
            padding: 8px 15px; 
            border-radius: 8px; 
            background-color: #fcfcfc; 
        }
        .filter-info table { border: none; margin-bottom: 0; width: auto; }
        .filter-info td { border: none; padding: 1px 5px; vertical-align: top; }
        
        /* Tabel Utama */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 6px 4px; word-wrap: break-word; vertical-align: top; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; font-size: 7.5pt; text-align: center; }
        
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        .footer-container { margin-top: 30px; width: 100%; }
        .footer-box { float: right; text-align: center; width: 250px; }
        .signature-space { margin-top: 50px; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h1>Laporan Detail Pengajuan Barang Logistik</h1>
        <p>Jurusan Komputer dan Bisnis - Politeknik Negeri Cilacap</p>
        
        {{-- KETERANGAN FILTER YANG DIGUNAKAN --}}
        <div class="filter-info">
            <table>
                <tr>
                    <td class="font-bold" width="100">Periode</td>
                    <td>: 
                        {{ request('bulan') ? \Carbon\Carbon::create()->month(request('bulan'))->translatedFormat('F') : 'Semua Bulan' }} 
                        {{ request('tahun') ?? 'Semua Tahun' }}
                    </td>
                </tr>
                <tr>
                    <td class="font-bold">Status Pengajuan</td>
                    <td>: <span class="uppercase">{{ request('status') ?? 'Semua Status' }}</span></td>
                </tr>
                @if(request('nama_bencana'))
                <tr>
                    <td class="font-bold">Filter Kejadian</td>
                    <td>: "{{ request('nama_bencana') }}"</td>
                </tr>
                @endif
                @if(request('search'))
                <tr>
                    <td class="font-bold">Cari Pegawai</td>
                    <td>: "{{ request('search') }}"</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="9%">Tgl Ajukan & Kejadian</th>
                <th width="14%">Lokasi & Kategori Bencana</th>
                <th width="10%">Pegawai Pengaju</th>
                <th width="16%">Nama Barang</th>
                <th width="8%">Penerima</th>
                <th width="6%">Jumlah</th>
                <th width="6%">Satuan</th>
                <th width="12%">Pembuat (Operator)</th>
                <th width="8%">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($data as $item)
                @php 
                    $details = $item->detailPengajuan;
                    $rowCount = count($details) > 0 ? count($details) : 1;
                @endphp

                @foreach($details as $index => $detail)
                <tr>
                    @if($index === 0)
                        <td rowspan="{{ $rowCount }}" class="text-center">{{ $no++ }}</td>
                        <td rowspan="{{ $rowCount }}" class="text-center">
                            <b>Ajukan:</b><br>{{ \Carbon\Carbon::parse($item->tgl_pengajuan)->format('d/m/Y') }}<br>
                            <hr style="border:0; border-top:1px solid #eee; margin:4px 0;">
                            <b>Bencana:</b><br>{{ \Carbon\Carbon::parse($item->bencana->tanggal)->format('d/m/Y') }}
                        </td>
                        <td rowspan="{{ $rowCount }}">
                            <b style="color: #b91c1c;">{{ $item->bencana->kategoriBencana->nama_kategori ?? 'N/A' }}</b><br>
                            Desa {{ $item->bencana->desa->nama_desa ?? '-' }}<br>
                            Kec. {{ $item->bencana->desa->kecamatan ?? '-' }}
                        </td>
                        <td rowspan="{{ $rowCount }}" class="text-center font-bold">{{ $item->pegawai->nama_pegawai ?? '-' }}</td>
                    @endif

                    {{-- Data Barang --}}
                    <td>{{ $detail->barang->nama_barang ?? 'N/A' }}</td>
                    <td class="text-center uppercase" style="font-size: 7pt;">{{ $detail->kategori_penerima ?? 'Warga' }}</td>
                    <td class="text-center font-bold">{{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $detail->barang->satuan ?? '-' }}</td>

                    @if($index === 0)
                        <td rowspan="{{ $rowCount }}" class="text-center">
                            <span class="font-bold">{{ $item->creator->nama ?? 'Sistem' }}</span><br>
                            <small style="color: #666;">ID: #{{ $item->created_by }}</small>
                        </td>
                        <td rowspan="{{ $rowCount }}" class="text-center font-bold uppercase">
                            {{ $item->status_pengajuan }}
                        </td>
                    @endif
                </tr>
                @endforeach
            @empty
            <tr>
                <td colspan="10" class="text-center" style="padding: 20px;">Data tidak ditemukan sesuai filter yang dipilih.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-container">
        <div style="float: left; width: 350px; font-size: 8pt; color: #555;">
            <b>Metadata Laporan:</b><br>
            Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB<br>
            Oleh: {{ Auth::user()->nama ?? 'System Administrator' }}<br>
            <i>Laporan ini adalah dokumen resmi Jurusan Komputer dan Bisnis PNC.</i>
        </div>
        <div class="footer-box">
            <p>Cilacap, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Mengetahui,</p>
            <br>
            <div class="signature-space">
                ( __________________________ )
            </div>
            <p>NIP. ........................................</p>
        </div>
    </div>

</body>
</html>