<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Detail Bencana</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }

        .kop {
            text-align: center;
            line-height: 1.3;
        }

        .kop h3,
        .kop h4,
        .kop p {
            margin: 2px 0;
        }

        .logo {
            position: absolute;
            left: 30px;
            top: 10px;
            width: 60px;
        }

        hr {
            border: 2px solid black;
            margin-top: 8px;
            margin-bottom: 15px;
        }

        .judul {
            text-align: center;
            margin-top: 10px;
        }

        .section {
            margin-top: 15px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 6px;
        }

        .table th {
            background: #eee;
        }

        .ttd {
            width: 100%;
            margin-top: 50px;
        }

        .ttd-kanan {
            width: 250px;
            float: right;
            text-align: center;
        }
    </style>

</head>

<body>

    <!-- LOGO -->
    <img src="{{ public_path('logo-dinsos.png') }}" class="logo">

    <!-- KOP -->
    <div class="kop">
        <h3>PEMERINTAH KABUPATEN CILACAP</h3>
        <h4>DINAS SOSIAL</h4>
        <p>Jl. Bromo Timur No. 13, Sidakaya, Cilacap</p>
        <p>Telp. (0282) 535093 | Kode Pos 53212</p>
    </div>

    <hr>

    <!-- JUDUL -->
    <div class="judul">
        <h3>LAPORAN DETAIL BENCANA</h3>
        <p>Kepada Kepala Dinas</p>
    </div>


    <!-- IDENTITAS -->
    <div class="section">

        <p><b>Nama Bencana:</b> {{ $data->nama_bencana }}</p>

        <p><b>Desa:</b> {{ $data->desa->nama_desa }}</p>

        <p><b>Kecamatan:</b> {{ $data->desa->kecamatan }}</p>

        <p><b>Kategori:</b> {{ $data->kategori->nama_kategori }}</p>

        <p><b>Tanggal Kejadian:</b>
            {{ \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y') }}
        </p>

        <p><b>Status Bencana:</b>
            {{ $data->status_bencana }}
        </p>

    </div>

    <hr>


    <!-- STATUS PENGADUAN -->
    <div class="section">

        <h3>Status Pengaduan</h3>

        <p>
            <b>Status:</b>
            {{ $data->pengaduan->status_pengaduan ?? '-' }}
        </p>

        <p>
            <b>Keterangan:</b>
            {{ $data->pengaduan->keterangan_verifikasi ?? '-' }}
        </p>

    </div>



    <!-- POSKO + DAPUR + DISTRIBUSI -->
    <div class="section">

        <h3>Data Posko, Dapur Umum & Distribusi Bantuan</h3>

        @if ($data->pengaduan && $data->pengaduan->poskos->count() > 0)

            @foreach ($data->pengaduan->poskos as $p)
                <p style="font-weight: bold; margin-top:15px;">
                    Data Posko {{ $loop->iteration }}
                </p>


                <!-- POSKO -->
                <table class="table">

                    <tr>
                        <td width="30%">Nama Posko</td>
                        <td>{{ $p->nama_posko }}</td>
                    </tr>

                    <tr>
                        <td>Lokasi</td>
                        <td>{{ $p->lokasi }}</td>
                    </tr>

                    <tr>
                        <td>Status</td>
                        <td>{{ ucfirst($p->status) }}</td>
                    </tr>

                </table>


                <!-- DAPUR UMUM -->
                <table class="table">

                    <tr>
                        <th>Nama Dapur Umum</th>
                        <th>Kapasitas</th>
                        <th>Jumlah Warga</th>
                        <th>Penanggung Jawab</th>
                    </tr>


                    @if ($p->dapurUmum && $p->dapurUmum->count() > 0)
                        @foreach ($p->dapurUmum as $d)
                            <tr>

                                <td>{{ $d->nama_dapur_umum }}</td>

                                <td>{{ $d->kapasitas_warga }}</td>

                                <td>{{ $d->jumlah_warga }}</td>

                                <td>{{ $d->penanggung_jawab }}</td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" style="text-align:center;">
                                Tidak ada data dapur umum
                            </td>
                        </tr>
                    @endif

                </table>



                <!-- DISTRIBUSI PER POSKO -->
                <table class="table">

                    <tr>
                        <th colspan="4">
                            Distribusi Bantuan Posko {{ $loop->iteration }}
                        </th>
                    </tr>

                    <tr>
                        <th>Tanggal</th>
                        {{-- <th>Lokasi</th> --}}
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                    </tr>


                    @php
                        $distribusiPosko = $data->distribusis->where('posko_id', $p->id);
                    @endphp


                    @forelse($distribusiPosko as $dis)
                        @foreach ($dis->detailDistribusis ?? [] as $detail)
                            <tr>

                                <td>
                                    {{ \Carbon\Carbon::parse($dis->tanggal_distribusi)->format('d-m-Y') }}
                                </td>

                                {{-- <td>
                                    {{ $dis->lokasi_distribusi }}
                                </td> --}}

                                <td>
                                    {{ $detail->detailBarangKeluar->first()->barang->nama_barang ?? '-' }}
                                </td>

                                <td>
                                    {{ $detail->jumlah_kirim }}
                                </td>

                                <td>
                                    {{ $detail->satuan }}
                                </td>
                            </tr>
                        @endforeach


                    @empty

                        <tr>
                            <td colspan="5" style="text-align:center;">
                                Belum ada distribusi bantuan pada posko ini
                            </td>
                        </tr>
                    @endforelse

                </table>

                <br>
            @endforeach
        @else
            <p>Belum terdapat data posko.</p>

        @endif

    </div>

    <!-- KESIMPULAN -->
    <div class="section">

        <h3>Kesimpulan</h3>

        <p>

            Berdasarkan data yang telah dihimpun,
            penanganan bencana telah dilakukan melalui
            pendirian posko serta distribusi bantuan
            kepada masyarakat terdampak.

            Saat ini kondisi bencana dinyatakan

            <b>{{ strtoupper($data->pengaduan->status_pengaduan ?? '-') }}</b>

        </p>

    </div>



    <div style="clear:both;"></div>


    <!-- TTD -->
    <div class="ttd">

        <div class="ttd-kanan">

            <p>
                Cilacap,
                {{ date('d-m-Y') }}
            </p>

            <p>
                Kepala Dinas Sosial
            </p>

            <br><br><br>

            <p>
                <b>(________________________)</b>
            </p>

        </div>

    </div>


</body>

</html>
