    @extends('layouts.app')

    @section('content')

    <div class="mx-3">
        <h2 class="text-xl font-bold">Tambah Data Distribusi</h2>
        <p class="text-gray-500 text-sm">
            Lengkapi data distribusi untuk memastikan penyaluran bantuan berjalan lancar
        </p>
    </div>

    <div class="bg-white rounded-xl p-6 m-3 mt-5 shadow">

        {{-- ERROR VALIDATION --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <b>Terjadi kesalahan:</b>
                <ul class="list-disc ml-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.management_distribusi.distribusi.store') }}" method="POST">
            @csrf

            <!-- ================= DATA UTAMA ================= -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label>Bencana *</label>
                    <select name="bencana_id" class="w-full border rounded-lg p-3" required>
                        <option value="">Pilih Bencana</option>
                        @foreach($bencana as $b)
                            <option value="{{ $b->id }}">{{ $b->nama_bencana }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Posko *</label>
                    <select name="posko_id"
                        class="w-full border rounded-lg p-3"
                        required onchange="autoPosko(this)">
                        <option value="">Pilih Posko</option>
                        @foreach($posko as $p)
                            <option value="{{ $p->id }}"
                                data-lokasi="{{ $p->lokasi ?? '' }}"
                                data-desa="{{ optional($p->desa)->nama_desa ?? '' }}">
                                {{ $p->nama_posko }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Tanggal Distribusi *</label>
                    <input type="date" name="tanggal_distribusi"
                        class="w-full border rounded-lg p-3" required>
                </div>

                <div>
                    <label>Lokasi Distribusi *</label>
                    <input type="text" name="lokasi_distribusi"
                        class="w-full border rounded-lg p-3 bg-gray-100"
                        readonly required>
                </div>

                <div>
                    <label>Desa Posko</label>
                    <input type="text" id="nama_desa"
                        class="w-full border rounded-lg p-3 bg-gray-100"
                        readonly>
                </div>

                <div>
                    <label>Kendaraan *</label>
                    <input type="text" name="kendaraan"
                        class="w-full border rounded-lg p-3" required>
                </div>

                <div>
                    <label>Nama Supir *</label>
                    <input type="text" name="nama_supir"
                        class="w-full border rounded-lg p-3" required>
                </div>

                <div>
                    <label>Nomor Kendaraan *</label>
                    <input type="text" name="nomor_kendaraan"
                        class="w-full border rounded-lg p-3" required>
                </div>

                <div>
                    <label>Kategori Distribusi *</label>
                    <select name="kategori_distribusi" class="w-full border rounded-lg p-3" required>
                        <option value="bencana">Bencana</option>
                        <option value="pasca_bencana">Pasca Bencana</option>
                    </select>
                </div>

                <div>
                    <label>Status *</label>
                    <select name="status" class="w-full border rounded-lg p-3" required>
                        <option value="pending">Pending</option>
                        <option value="dikirim">Dikirim</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label>Keterangan</label>
                    <textarea name="keterangan"
                        class="w-full border rounded-lg p-3"
                        rows="3"></textarea>
                </div>

            </div>
        <!-- DEBUG -->
        <div class="bg-yellow-100 p-2 mb-3 rounded">
            Jumlah Data Barang Keluar:
            {{ count($barangKeluar) }}
        </div>

            <!-- ================= DETAIL BARANG ================= -->
            <h3 class="font-bold mt-8 mb-3">Detail Barang</h3>

            <div class="overflow-x-auto">
                <table class="w-full border text-sm text-center">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">Nama Barang</th>
                            <th class="p-2 border">Jumlah Keluar</th>
                            <th class="p-2 border">Jumlah Kirim</th>
                            <th class="p-2 border">Satuan</th>
                        </tr>
                    </thead>

<tbody>
@forelse($barangKeluar as $bk)

<tr>

    {{-- Nama Barang --}}
    <td class="border p-2">
        {{ $bk->barang->nama_barang ?? '-' }}
    </td>

    {{-- Jumlah Keluar --}}
    <td class="border p-2 text-center">
        {{ $bk->jumlah_keluar }}
    </td>

    {{-- Jumlah Kirim --}}
    <td class="border p-2">
        <input
            type="number"
            name="barang_detail[{{ $loop->index }}][jumlah_kirim]"
            value="{{ $bk->jumlah_keluar }}"
            min="1"
            max="{{ $bk->jumlah_keluar }}"
            data-max="{{ $bk->jumlah_keluar }}"
            class="w-full border rounded p-2 text-center"
            oninput="validasiInput(this)"
            required>
    </td>

    {{-- Satuan --}}
    <td class="border p-2 text-center">

        {{ $bk->barang->satuan ?? '-' }}

        <input
            type="hidden"
            name="barang_detail[{{ $loop->index }}][satuan]"
            value="{{ $bk->barang->satuan }}">

    </td>

    {{-- Hidden Detail Barang Keluar --}}
    <input
        type="hidden"
        name="barang_detail[{{ $loop->index }}][detail_barang_keluar_id]"
        value="{{ $bk->id }}">

</tr>

@empty

<tr>
    <td colspan="4" class="text-center p-4 text-gray-500">
        Tidak ada data barang keluar
    </td>
</tr>

@endforelse
</tbody>
                </table>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.management_distribusi.distribusi.index') }}"
                class="px-4 py-2 bg-gray-300 rounded-lg">
                    Batal
                </a>

                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg">
                    Simpan Data
                </button>
            </div>

        </form>
    </div>

    <!-- ================= SCRIPT ================= -->
    <script>
    function autoPosko(select) {
        let option = select.options[select.selectedIndex];

        document.querySelector('input[name="lokasi_distribusi"]').value =
            option.getAttribute('data-lokasi') ?? '';

        document.getElementById('nama_desa').value =
            option.getAttribute('data-desa') ?? '';
    }

    function validasiInput(input){

    let max = parseInt(input.dataset.max);

    if(parseInt(input.value) > max){
        input.value = max;
    }

    if(parseInt(input.value) < 1 || isNaN(parseInt(input.value))){
        input.value = 1;
    }

}
    </script>

    @endsection