{{-- resources/views/distribusi_bantuan/barang_keluar/export.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Distribusi Logistik - Politeknik Negeri Cilacap</title>
    <style>
        @page { size: A4 landscape; margin: 1cm; }
        body { font-family: 'Arial', sans-serif; font-size: 8.5pt; color: #333; line-height: 1.4; }
        
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 14pt; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 10pt; }

        .filter-info { 
            margin: 15px auto; 
            font-size: 8pt; 
            display: table; 
            text-align: left; 
            border: 1px solid #ddd; 
            padding: 8px 15px; 
            border-radius: 8px; 
            background-color: #fcfcfc; 
        }
        .filter-info table { border: none; margin-bottom: 0; width: auto; }
        .filter-info td { border: none; padding: 1px 5px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 6px 4px; word-wrap: break-word; vertical-align: top; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; font-size: 8pt; text-align: center; }
        
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .status-badge { font-weight: bold; text-transform: uppercase; font-size: 7.5pt; color: #1e40af; }
        .note-text { font-size: 7.5pt; color: #555; font-style: italic; }

        .footer-container { margin-top: 30px; width: 100%; }
        .footer-box { float: right; text-align: center; width: 250px; }
        .signature-space { margin-top: 50px; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h1>Laporan Pengeluaran Barang Logistik (Distribusi)</h1>
        <p>Jurusan Komputer dan Bisnis - Politeknik Negeri Cilacap</p>
        
        <div class="filter-info">
            <table>
                <tr>
                    <td class="font-bold" width="100">Periode</td>
                    <td>: 
                        {{ request('bulan') ? \Carbon\Carbon::create()->month(request('bulan'))->translatedFormat('F') : 'Semua Bulan' }} 
                        {{ request('tahun') ? request('tahun') : 'Semua Tahun' }}
                    </td>
                </tr>
                <tr>
                    <td class="font-bold">Gudang Sumber</td>
                    <td>: 
                        @php $g = request('gudang_id') ? \App\Models\Gudang::find(request('gudang_id')) : null; @endphp
                        {{ $g ? $g->nama_gudang : 'Semua Gudang' }}
                    </td>
                </tr>
                <tr>
                    <td class="font-bold">Status Proses</td>
                    <td>: <span class="uppercase">{{ request('status') ?? 'Semua Status' }}</span></td>
                </tr>
                @if(request('nama_bencana'))
                <tr>
                    <td class="font-bold">Filter Kejadian</td>
                    <td>: "{{ request('nama_bencana') }}"</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="9%">Tgl Keluar</th>
                <th width="15%">Referensi & Lokasi Tujuan</th>
                <th width="12%">Gudang & Petugas (PJ)</th>
                <th width="15%">Nama Barang</th>
                <th width="6%">Minta</th>
                <th width="6%">Keluar</th>
                <th width="6%">Satuan</th>
                <th width="16%">Catatan Detail / Umum</th>
                <th width="12%">Status & Operator</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($data as $item)
                @php 
                    $details = $item->detailBarangKeluar;
                    $rowCount = count($details) > 0 ? count($details) : 1;
                @endphp

                @foreach($details as $index => $detail)
                <tr>
                    @if($index === 0)
                        <td rowspan="{{ $rowCount }}" class="text-center">{{ $no++ }}</td>
                        <td rowspan="{{ $rowCount }}" class="text-center">
                            {{ \Carbon\Carbon::parse($item->tgl_keluar)->format('d/m/Y') }}
                        </td>
                        <td rowspan="{{ $rowCount }}">
                            <div class="font-bold text-indigo-700">Ref: #{{ $item->pengajuan_barang_id }}</div>
                            <div class="uppercase" style="font-size: 7.5pt; margin-top: 2px;">
                                <b>{{ $item->pengajuanBarang->bencana->kategoriBencana->nama_kategori ?? '-' }}</b><br>
                                Desa {{ $item->pengajuanBarang->bencana->desa->nama_desa ?? '-' }}
                            </div>
                        </td>
                        <td rowspan="{{ $rowCount }}">
                            <div class="font-bold uppercase" style="font-size: 8pt;">{{ $item->gudang->nama_gudang ?? '-' }}</div>
                            <div style="font-size: 7.5pt; color: #555;">PJ: {{ $item->petugasGudang->nama_pegawai ?? '-' }}</div>
                        </td>
                    @endif

                    {{-- Data Barang --}}
                    <td>{{ $detail->barang->nama_barang ?? 'N/A' }}</td>
                    <td class="text-center">{{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                    <td class="text-center font-bold">{{ number_format($detail->jumlah_keluar, 0, ',', '.') }}</td>
                    <td class="text-center uppercase" style="font-size: 7.5pt;">{{ $detail->barang->satuan ?? '-' }}</td>
                    
                    <td>
                        {{-- Logika Catatan: Menampilkan Catatan Per Item DAN Catatan Umum --}}
                        @if($detail->catatan)
                            <div class="note-text"><b>Item:</b> {{ $detail->catatan }}</div>
                        @endif
                        
                        @if($index === 0 && $item->catatan)
                            <div class="note-text" style="margin-top: 4px; color: #b91c1c;">
                                <b>Instruksi Umum:</b><br>{{ $item->catatan }}
                            </div>
                        @endif

                        @if(!$detail->catatan && !($index === 0 && $item->catatan))
                            <span style="color: #ccc;">-</span>
                        @endif
                    </td>

                    @if($index === 0)
                        <td rowspan="{{ $rowCount }}" class="text-center">
                            <div class="status-badge">{{ $item->status_proses }}</div>
                            <hr style="border:0; border-top:1px solid #eee; margin:4px 0;">
                            <div style="font-size: 7pt; color: #666;">
                                <b>Diperbarui:</b><br>
                                {{ $item->updater->nama ?? 'Sistem' }}
                            </div>
                        </td>
                    @endif
                </tr>
                @endforeach
            @empty
            <tr>
                <td colspan="10" class="text-center" style="padding: 30px;">Data tidak ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-container">
        <div style="float: left; width: 400px; font-size: 8pt; color: #666;">
            <b>Catatan Internal:</b><br>
            - Dokumen ini sah dan dicetak dari Sistem Logistik PNC.<br>
            - Waktu Cetak: {{ date('d/m/Y H:i') }} WIB.<br>
            - Operator: {{ Auth::user()->nama }} (ID: #{{ Auth::id() }}).
        </div>
        <div class="footer-box">
            <p>Cilacap, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Penanggung Jawab Gudang,</p>
            <br><br>
            <div class="signature-space">
                ( __________________________ )
            </div>
            <p>NIP. ........................................</p>
        </div>
    </div>

</body>
</html>