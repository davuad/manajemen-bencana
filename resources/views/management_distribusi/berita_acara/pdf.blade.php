<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Berita Acara Distribusi</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 30px;
            color: #000;
        }

        .toolbar {
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
            margin-right: 10px;
        }

        .btn-back {
            background: #6b7280;
        }

        .btn-print {
            background: #16a34a;
        }

        .kop {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop h2,
        .kop h3,
        .kop p {
            margin: 0;
        }

        .judul {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .judul h3 {
            margin: 0;
            text-decoration: underline;
        }

        .info {
            width: 100%;
            margin-bottom: 20px;
        }

        .info td {
            padding: 4px;
            vertical-align: top;
        }

        .tabel {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .tabel th,
        .tabel td {
            border: 1px solid #000;
            padding: 8px;
        }

        .tabel th {
            background: #e5e7eb;
            text-align: center;
        }

        .paragraf {
            margin-top: 20px;
            text-align: justify;
            line-height: 1.8;
        }

        .ttd {
            width: 100%;
            margin-top: 60px;
        }

        .ttd td {
            width: 50%;
            text-align: center;
        }

        @media print {

            .toolbar {
                display: none;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>

    {{-- Tombol --}}
    <div class="toolbar">

        <a href="{{ route('admin.management_distribusi.distribusi.index') }}"
            class="btn btn-back">
            ← Kembali
        </a>

        <a href="javascript:window.print()"
            class="btn btn-print">
            🖨 Cetak / Download PDF
        </a>

    </div>

    {{-- KOP SURAT --}}
    <div class="kop">

        <h2>PEMERINTAH KABUPATEN CILACAP</h2>

        <h3>BADAN PENANGGULANGAN BENCANA DAERAH (BPBD)</h3>

        <p>
            Jl. Dr. Soetomo No. XX, Cilacap
        </p>

    </div>

    {{-- JUDUL --}}
    <div class="judul">

        <h3>BERITA ACARA DISTRIBUSI BANTUAN</h3>

        <p>
            Nomor :
            BA/{{ $distribusi->id }}/BPBD/{{ date('Y') }}
        </p>

    </div>

    {{-- DATA DISTRIBUSI --}}
    <table class="info">

        <tr>
            <td width="180">Nama Bencana</td>
            <td width="10">:</td>
            <td>{{ $distribusi->bencana->nama_bencana ?? '-' }}</td>
        </tr>

        <tr>
            <td>Posko</td>
            <td>:</td>
            <td>{{ $distribusi->posko->nama_posko ?? '-' }}</td>
        </tr>

        <tr>
            <td>Desa</td>
            <td>:</td>
            <td>{{ optional($distribusi->posko->desa)->nama_desa ?? '-' }}</td>
        </tr>

        <tr>
            <td>Tanggal Distribusi</td>
            <td>:</td>
            <td>{{ $distribusi->tanggal_distribusi }}</td>
        </tr>

        <tr>
            <td>Lokasi Distribusi</td>
            <td>:</td>
            <td>{{ $distribusi->lokasi_distribusi }}</td>
        </tr>

        <tr>
            <td>Kendaraan</td>
            <td>:</td>
            <td>{{ $distribusi->kendaraan }}</td>
        </tr>

        <tr>
            <td>Nama Supir</td>
            <td>:</td>
            <td>{{ $distribusi->nama_supir }}</td>
        </tr>

        <tr>
            <td>Nomor Kendaraan</td>
            <td>:</td>
            <td>{{ $distribusi->nomor_kendaraan }}</td>
        </tr>

        <tr>
            <td>Status</td>
            <td>:</td>
            <td>{{ ucfirst($distribusi->status) }}</td>
        </tr>

    </table>

    {{-- TABEL BARANG --}}
    <h4>Daftar Barang Distribusi</h4>

    <table class="tabel">

        <thead>
            <tr>
                <th width="50">No</th>
                <th>Nama Barang</th>
                <th width="120">Jumlah Keluar</th>
                <th width="120">Jumlah Kirim</th>
                <th width="100">Satuan</th>
            </tr>
        </thead>

        <tbody>

            @forelse($distribusi->detailDistribusis as $detail)

                <tr>

                    <td align="center">
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        {{ $detail->detailBarangKeluar->barang->nama_barang ?? '-' }}
                    </td>

                    <td align="center">
                        {{ $detail->detailBarangKeluar->jumlah_keluar ?? 0 }}
                    </td>

                    <td align="center">
                        {{ $detail->jumlah_kirim }}
                    </td>

                    <td align="center">
                        {{ $detail->satuan }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" align="center">
                        Tidak ada data barang
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

    {{-- ISI BERITA ACARA --}}
    <div class="paragraf">

        Pada hari ini telah dilaksanakan kegiatan distribusi bantuan
        kepada masyarakat terdampak bencana
        <b>{{ $distribusi->bencana->nama_bencana ?? '-' }}</b>
        yang berlokasi di
        <b>{{ $distribusi->lokasi_distribusi }}</b>.

        Bantuan telah diterima dalam kondisi baik dan sesuai dengan
        daftar barang yang tercantum pada berita acara ini.

        Demikian berita acara ini dibuat dengan sebenarnya untuk
        dipergunakan sebagaimana mestinya.

    </div>

    {{-- TANDA TANGAN --}}
    <table class="ttd">

        <tr>

            <td>
                Mengetahui,
                <br>
                Kepala Posko
                <br><br><br><br><br><br>

                ______________________
            </td>

            <td>
                Petugas Distribusi
                <br><br><br><br><br><br>

                ______________________
            </td>

        </tr>

    </table>

</body>

</html>