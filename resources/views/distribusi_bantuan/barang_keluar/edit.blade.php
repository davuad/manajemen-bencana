{{-- 
gadipake  --}}



@extends('layouts.app')

@section('content')
<div class="mx-3">
    <h2 class="text-xl font-bold text-gray-800">Edit Barang Keluar</h2>
    <p class="text-gray-500 text-sm">Perbarui status pengiriman atau daftar barang yang direalisasikan.</p>
</div>

<div class="bg-white rounded-xl p-6 m-3 mt-5 shadow border border-gray-100">
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 text-sm">
            <p class="font-bold">Gagal menyimpan perubahan:</p>
            <ul class="list-disc ml-5 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="editForm" action="{{ route('distribusi_bantuan.barang_keluar.update', $data->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <input type="hidden" name="pengajuan_barang_id" value="{{ $data->pengajuan_barang_id }}">
        <input type="hidden" name="gudang_id" value="{{ $data->gudang_id }}">
        <input type="hidden" name="petugas_gudang_id" value="{{ $data->petugas_gudang_id }}">
        <input type="hidden" name="tgl_keluar" value="{{ $data->tgl_keluar }}">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 bg-blue-50/50 p-5 rounded-xl border border-blue-100">
            <div>
                <label class="text-blue-400 block uppercase text-[10px] font-bold tracking-widest mb-1">ID Pengajuan</label>
                <p class="font-bold text-blue-900 uppercase">#{{ $data->pengajuan_barang_id }}</p>
            </div>
            <div>
                <label class="text-blue-400 block uppercase text-[10px] font-bold tracking-widest mb-1">Gudang Asal</label>
                <p class="font-bold text-blue-900 uppercase">{{ $data->gudang->nama_gudang ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-blue-400 block uppercase text-[10px] font-bold tracking-widest mb-1">Petugas Gudang</label>
                <p class="font-bold text-blue-900">{{ $data->petugasGudang->nama_pegawai ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-blue-400 block uppercase text-[10px] font-bold tracking-widest mb-1">Tgl Keluar</label>
                <p class="font-bold text-blue-900">{{ \Carbon\Carbon::parse($data->tgl_keluar)->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="mb-10 max-w-md">
            <label class="block font-medium mb-1 text-gray-700">Update Status Proses *</label>
            <select name="status_proses" class="w-full border rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                <option value="diproses" {{ $data->status_proses == 'diproses' ? 'selected' : '' }}>PENDING (Diproses)</option>
                <option value="dikirim" {{ $data->status_proses == 'dikirim' ? 'selected' : '' }}>DIKIRIM (Dalam Pengiriman)</option>
                <option value="selesai" {{ $data->status_proses == 'selesai' ? 'selected' : '' }}>SELESAI (Sudah Distribusi)</option>
                <option value="dibatalkan" {{ $data->status_proses == 'dibatalkan' ? 'selected' : '' }}>BATAL (Dibatalkan)</option>
            </select>
        </div>



<table class="w-full text-sm border-collapse">
    <thead>
        <tr class="bg-gray-50">
            <th class="p-3 border-b text-left">Nama Barang</th>
            <th class="p-3 border-b text-center">Jumlah Diajukan</th>
            <th class="p-3 border-b text-center w-32">Jumlah Keluar</th>
            <th class="p-3 border-b text-left">Catatan Barang</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data->pengajuanBarang->detailPengajuan as $index => $detail)
        <tr>
            <td class="p-3 border-b">
                {{ $detail->barang->nama_barang }}
                <input type="hidden" name="barang_id[]" value="{{ $detail->barang_id }}">
            </td>
            <td class="p-3 border-b text-center bg-gray-50">
                <span class="font-bold">{{ $detail->jumlah }}</span> {{ $detail->barang->satuan }}
            </td>
            <td class="p-3 border-b">
                <input type="number" 
                       name="jumlah[]" 
                       value="{{ $detail->jumlah }}" 
                       max="{{ $detail->jumlah }}" 
                       class="w-full border rounded p-2 text-center focus:ring-blue-500" 
                       required>
                <p class="text-[10px] text-red-500 mt-1">* Maks {{ $detail->jumlah }}</p>
            </td>
            <td class="p-3 border-b">
                <input type="text" name="catatan_barang[]" class="w-full border rounded p-2" placeholder="Contoh: Stok terbatas">
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


        </div>

        <div class="flex justify-end gap-3 mt-8 border-t pt-6">
            <a href="{{ route('distribusi_bantuan.barang_keluar.index') }}" class="px-5 py-2 bg-gray-200 rounded-lg text-sm font-medium hover:bg-gray-300">Batal</a>
            <button type="submit" class="px-8 py-2 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 font-bold">
                Perbarui Data Barang Keluar
            </button>
        </div>
    </form>
</div>

<div id="saveModal" class="fixed inset-0 backdrop-blur-sm bg-black/30 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 text-center">
        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
            <x-heroicon-o-check-circle class="w-10 h-10 text-blue-600"/>
        </div>
        <h3 class="text-lg font-bold text-gray-900">Simpan Perubahan?</h3>
        <p class="text-sm text-gray-500 mt-2">Pastikan semua data sudah sesuai dengan realisasi di lapangan.</p>
        <div class="flex justify-center gap-3 mt-6">
            <button type="button" onclick="closeSaveModal()" class="px-6 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm">Cek Lagi</button>
            <button type="button" onclick="submitEditForm()" class="px-6 py-2 rounded-lg bg-blue-600 text-white text-sm shadow-md font-bold">Ya, Simpan</button>
        </div>
    </div>
</div>

<table class="hidden">
    <tbody id="row-template">
        <tr class="item-row">
            <td class="p-3 border-b">
                <select name="barang_id[]" class="w-full border rounded p-2 text-sm" required>
                    <option value="">Pilih Barang</option>
                    @foreach($barang as $b)
                        <option value="{{ $b->id }}">{{ $b->nama_barang }} ({{ $b->satuan }})</option>
                    @endforeach
                </select>
            </td>
            <td class="p-3 border-b">
                <input type="number" name="jumlah[]" class="w-full border rounded p-2 text-sm" min="1" required>
            </td>
            <td class="p-3 border-b text-center">
                <button type="button" class="text-red-500 font-bold remove-row">Hapus</button>
            </td>
        </tr>
    </tbody>
</table>

<script>
    document.getElementById('add-row').addEventListener('click', function() {
        const tbody = document.getElementById('barang-body');
        const template = document.getElementById('row-template').innerHTML;
        tbody.insertAdjacentHTML('beforeend', template);
    });

    document.getElementById('barang-body').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row')) {
            const rows = document.querySelectorAll('.item-row');
            if(rows.length > 1) {
                e.target.closest('tr').remove();
            } else {
                alert('Minimal satu jenis barang harus terdata.');
            }
        }
    });

    const editForm = document.getElementById('editForm');
    const saveModal = document.getElementById('saveModal');

    editForm.addEventListener('submit', function(e) {
        e.preventDefault(); 
        saveModal.classList.remove('hidden');
        saveModal.classList.add('flex');
    });

    function closeSaveModal() {
        saveModal.classList.add('hidden');
        saveModal.classList.remove('flex');
    }

    function submitEditForm() {
        editForm.submit(); 
    }
</script>
@endsection