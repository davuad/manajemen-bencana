@extends('layouts.app')

@section('content')
{{-- Load TomSelect Assets --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<div class="mx-3 flex justify-between items-center mb-5">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Perbaiki Rincian Pengajuan</h2>
        <p class="text-gray-500 text-sm">Sesuaikan rincian barang. Identitas pengaju dan waktu asli tetap dipertahaman.</p>
    </div>
    <a href="{{ route('admin.management_distribusi.pengajuan_barang.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm transition font-medium">
        &larr; Batal
    </a>
</div>

<div class="bg-white rounded-2xl p-6 m-3 shadow-sm border border-gray-100">
    
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6 text-sm">
            <p class="font-bold">Gagal menyimpan perubahan:</p>
            <ul class="list-disc ml-5 mt-1">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    {{-- Info Jejak Audit --}}
    <div class="mb-8 p-4 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="bg-indigo-600 p-3 rounded-xl text-white shadow-md">
                <x-heroicon-o-finger-print class="w-6 h-6"/>
            </div>
            <div>
                <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest leading-none mb-1">Otoritas Pengaju Asli</p>
                <p class="text-sm text-indigo-900 font-bold">
                    Dokumen diajukan oleh <span class="text-indigo-600">{{ $data->pegawai->nama_pegawai ?? '-' }}</span> 
                    pada {{ \Carbon\Carbon::parse($data->tgl_pengajuan)->translatedFormat('d F Y') }}
                </p>
            </div>
        </div>
    </div>

    {{-- 🟢 PROTEKSI STATUS: Jika status sudah bukan pending, kunci form dan tampilkan peringatan --}}
    @if($data->status_pengajuan !== 'pending')
        <div class="p-6 bg-amber-50 border border-amber-200 rounded-2xl text-center">
            <x-heroicon-o-lock-closed class="w-12 h-12 text-amber-600 mx-auto mb-3"/>
            <h4 class="text-lg font-bold text-amber-900 uppercase">Dokumen Telah Dikunci</h4>
            <p class="text-sm text-amber-700 mt-1">
                Pengajuan ini sudah berstatus <b class="uppercase">[{{ $data->status_pengajuan }}]</b>. Data yang telah diproses oleh pimpinan atau masuk ke gudang tidak diperbolehkan untuk diubah kembali demi validitas laporan.
            </p>
            <a href="{{ route('admin.management_distribusi.pengajuan_barang.index') }}" class="mt-4 inline-block px-6 py-2.5 bg-amber-600 text-white font-bold rounded-xl text-xs uppercase tracking-widest hover:bg-amber-700 transition">Kembali ke Index</a>
        </div>
    @else

    <form id="editForm" action="{{ route('admin.management_distribusi.pengajuan_barang.update', $data->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- SEARCHABLE DROPDOWN BENCANA --}}
            <div class="md:col-span-2">
                <label class="block font-bold text-gray-700 mb-1 text-sm uppercase tracking-tight">Lokasi & Kejadian Bencana *</label>
                <select name="bencana_id" id="select-bencana" class="w-full" required>
                    @foreach($bencana as $b)
                        <option value="{{ $b->id }}" 
                            {{ $data->bencana_id == $b->id ? 'selected' : '' }}
                            data-desa="{{ $b->desa->nama_desa ?? 'N/A' }}"
                            data-kecamatan="{{ $b->desa->kecamatan ?? '-' }}"
                            data-kategori="{{ $b->kategoriBencana->nama_kategori ?? 'Bencana' }}"
                            data-waktu="{{ $b->pengaduan ? $b->pengaduan->created_at->format('d M Y, H:i') : 'Data Manual' }}"
                            data-rusak="{{ $b->tingkat_kerusakan }}">
                            {{ $b->desa->nama_desa }} - {{ $b->kategoriBencana->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- PEGAWAI: LOCKED --}}
            <div>
                <label class="block font-bold text-gray-400 mb-1 text-sm uppercase tracking-tight">Pengaju (Terkunci)</label>
                <div class="w-full bg-gray-50 border border-gray-200 text-gray-400 rounded-xl p-3 font-bold text-sm flex items-center gap-2 cursor-not-allowed shadow-inner">
                    <x-heroicon-o-lock-closed class="w-4 h-4"/>
                    {{ $data->pegawai->nama_pegawai ?? '-' }}
                </div>
                <input type="hidden" name="pegawai_id" value="{{ $data->pegawai_id }}">
            </div>

            {{-- TANGGAL: LOCKED --}}
            <div>
                <label class="block font-bold text-gray-400 mb-1 text-sm uppercase tracking-tight">Tgl Pengajuan (Terkunci)</label>
                <div class="w-full bg-gray-50 border border-gray-200 text-gray-400 rounded-xl p-3 font-bold text-sm flex items-center gap-2 cursor-not-allowed shadow-inner">
                    <x-heroicon-o-calendar class="w-4 h-4"/>
                    {{ \Carbon\Carbon::parse($data->tgl_pengajuan)->format('d/m/Y') }}
                </div>
                <input type="hidden" name="tgl_pengajuan" value="{{ $data->tgl_pengajuan }}">
            </div>

            <div>
                <label class="block font-bold text-gray-700 mb-1 text-sm uppercase tracking-tight">Status Pengajuan</label>
                <div class="w-full bg-amber-100 text-amber-700 border border-amber-200 rounded-xl p-3 font-black text-sm flex items-center justify-between shadow-sm">
                    <span class="flex items-center gap-2 uppercase tracking-widest">
                        <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                        {{ $data->status_pengajuan }}
                    </span>
                    <x-heroicon-o-shield-check class="w-5 h-5 opacity-50"/>
                </div>
                <input type="hidden" name="status_pengajuan" value="{{ $data->status_pengajuan }}">
            </div>
        </div>

        {{-- Section Daftar Barang --}}
        <div class="border-t border-gray-100 pt-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-black text-indigo-700 flex items-center gap-2">
                    <x-heroicon-o-shopping-cart class="w-6 h-6"/>
                    Rincian Barang Logistik
                </h3>
                <button type="button" id="add-row" class="bg-green-600 text-white px-5 py-2 rounded-xl text-xs font-bold hover:bg-green-700 transition shadow-lg flex items-center gap-2">
                    <x-heroicon-o-plus-circle class="w-4 h-4"/>
                    Tambah Baris Barang
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse" id="barang-table">
                    <thead>
                        <tr class="bg-gray-50 text-left text-gray-500 uppercase text-[10px] font-black tracking-widest">
                            <th class="p-4 border-b w-1/3">Nama Barang</th>
                            <th class="p-4 border-b">Kategori Penerima</th>
                            <th class="p-4 border-b w-1/6 text-center">Jumlah</th>
                            <th class="p-4 border-b text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="barang-body">
                        @foreach($data->detailPengajuan as $detail)
                        <tr class="item-row hover:bg-gray-50/50 transition">
                            <td class="p-3 border-b">
                                <select name="barang_id[]" class="w-full border-gray-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($barang as $b)
                                        {{-- 🟢 PERBAIKAN: Ganti dari $b->id menjadi $b->id_barang sesuai Primary Key Database --}}
                                        <option value="{{ $b->id_barang }}" {{ $detail->barang_id == $b->id_barang ? 'selected' : '' }}>
                                            {{ $b->nama_barang }} ({{ $b->satuan }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-3 border-b">
                                <select name="kategori_penerima[]" class="w-full border-gray-200 rounded-xl p-2.5 text-sm">
                                    <option value="warga" {{ $detail->kategori_penerima == 'warga' ? 'selected' : '' }}>Warga Terdampak</option>
                                    <option value="pengungsi" {{ $detail->kategori_penerima == 'pengungsi' ? 'selected' : '' }}>Pengungsi</option>
                                    <option value="lansia" {{ $detail->kategori_penerima == 'lansia' ? 'selected' : '' }}>Lansia</option>
                                    <option value="anak-anak" {{ $detail->kategori_penerima == 'anak-anak' ? 'selected' : '' }}>Anak-anak</option>
                                    <option value="relawan" {{ $detail->kategori_penerima == 'relawan' ? 'selected' : '' }}>Relawan</option>
                                </select>
                            </td>
                            <td class="p-3 border-b">
                                <input type="number" name="jumlah[]" value="{{ $detail->jumlah }}" class="w-full border-gray-200 rounded-xl p-2.5 text-center font-black text-indigo-600 focus:ring-2 focus:ring-indigo-500 outline-none" min="1" required>
                            </td>
                            <td class="p-3 border-b text-center">
                                <button type="button" class="text-red-500 p-2 hover:bg-red-50 rounded-full transition remove-row" title="Hapus Baris">
                                    <x-heroicon-o-trash class="w-5 h-5 pointer-events-none"/>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8">
            <label class="block font-bold text-gray-700 mb-1 text-sm uppercase tracking-tight">Keterangan / Catatan Tambahan</label>
            <textarea name="keterangan" rows="3" class="w-full border-gray-200 rounded-2xl p-4 text-sm outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-inner bg-gray-50/30" placeholder="Tulis alasan perubahan atau catatan khusus...">{{ $data->keterangan }}</textarea>
        </div>

        <div class="flex justify-end gap-3 mt-10 border-t pt-8">
            <a href="{{ route('admin.management_distribusi.pengajuan_barang.index') }}" class="px-8 py-3 bg-gray-100 text-gray-600 rounded-xl text-sm font-bold uppercase tracking-widest hover:bg-gray-200 transition">Batal</a>
            <button type="submit" class="px-10 py-3 bg-indigo-600 text-white rounded-xl shadow-xl hover:bg-indigo-700 font-bold uppercase tracking-widest text-sm transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
    @endif
</div>

{{-- MODAL KONFIRMASI --}}
<div id="saveModal" class="fixed inset-0 backdrop-blur-sm bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 text-center transform transition-all scale-100">
        <div class="bg-indigo-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 text-indigo-600">
            <x-heroicon-o-question-mark-circle class="w-12 h-12"/>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">Perbarui Data?</h3>
        <p class="text-sm text-gray-500 mt-2 leading-relaxed">
            Perubahan pada rincian barang akan disimpan. Pastikan jumlah dan lokasi sudah akurat sesuai instruksi lapangan.
        </p>
        <div class="flex flex-col gap-2 mt-8">
            <button type="button" onclick="submitEditForm()" class="w-full py-3 rounded-xl bg-indigo-600 text-white font-bold shadow-lg hover:bg-indigo-700 transition">Ya, Perbarui Sekarang</button>
            <button type="button" onclick="closeSaveModal()" class="w-full py-3 rounded-xl bg-gray-50 text-gray-500 font-bold hover:bg-gray-100 transition">Periksa Kembali</button>
        </div>
    </div>
</div>

{{-- ROW TEMPLATE (Hidden) --}}
<table class="hidden">
    <tbody id="row-template">
        <tr class="item-row hover:bg-gray-50/50 transition">
            <td class="p-3 border-b">
                <select name="barang_id[]" class="w-full border-gray-200 rounded-lg p-2 text-sm focus:ring-indigo-500" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach($barang as $item)
                        <option value="{{ $item->id_barang }}">{{ $item->nama_barang }} ({{ $item->satuan }})</option>
                    @endforeach
                </select>
            </td>
            <td class="p-3 border-b">
                <select name="kategori_penerima[]" class="w-full border-gray-200 rounded-xl p-2.5 text-sm">
                    <option value="warga">Warga Terdampak</option>
                    <option value="pengungsi">Pengungsi</option>
                    <option value="lansia">Lansia</option>
                    <option value="anak-anak">Anak-anak</option>
                    <option value="relawan">Relawan</option>
                </select>
            </td>
            <td class="p-3 border-b">
                <input type="number" name="jumlah[]" class="w-full border-gray-200 rounded-xl p-2.5 text-center font-black text-indigo-600" min="1" required>
            </td>
            <td class="p-3 border-b text-center">
                <button type="button" class="text-red-500 p-2 hover:bg-red-50 rounded-full transition remove-row">
                    <x-heroicon-o-trash class="w-5 h-5 pointer-events-none"/>
                </button>
            </td>
        </tr>
    </tbody>
</table>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Inisialisasi TomSelect (Style Pencarian Bencana)
        new TomSelect("#select-bencana", {
            create: false,
            sortField: { field: "text", direction: "asc" },
            render: {
                option: function(data, escape) {
                    return `
                        <div class="py-3 px-4 border-b border-gray-50">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold text-indigo-700 text-base">${escape(data.desa)}</span>
                                <span class="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold uppercase">${escape(data.rusak)}</span>
                            </div>
                            <div class="flex gap-4">
                                <div class="text-[11px] text-gray-500">
                                    <span class="font-medium text-gray-400 uppercase">Kec:</span> ${escape(data.kecamatan)}
                                </div>
                                <div class="text-[11px] text-gray-500">
                                    <span class="font-medium text-gray-400 uppercase">Jenis:</span> ${escape(data.kategori)}
                                </div>
                            </div>
                            <div class="text-[10px] text-indigo-400 mt-1 italic font-medium tracking-tight">
                                 Waktu: ${escape(data.waktu)}
                            </div>
                        </div>`;
                },
                item: function(data, escape) {
                    return `<div class="font-bold text-indigo-700">
                                ${escape(data.desa)} - <span class="text-gray-500 font-normal text-xs">${escape(data.kategori)}</span> 
                            </div>`;
                }
            }
        });

        // 2. Logika Tambah & Hapus Baris
        document.getElementById('add-row').addEventListener('click', function() {
            const tbody = document.getElementById('barang-body');
            const template = document.getElementById('row-template').innerHTML;
            tbody.insertAdjacentHTML('beforeend', template);
        });

        document.getElementById('barang-body').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row') || e.target.closest('.remove-row')) {
                const rows = document.querySelectorAll('#barang-body .item-row');
                if(rows.length > 1) {
                    const targetRow = e.target.closest('tr');
                    targetRow.remove();
                } else {
                    alert('Minimal harus ada satu rincian barang.');
                }
            }
        });

        // 3. Logika Modal
        const editForm = document.getElementById('editForm');
        const saveModal = document.getElementById('saveModal');

        if(editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault(); 
                saveModal.classList.remove('hidden');
                saveModal.classList.add('flex');
            });
        }
    });

    function closeSaveModal() {
        document.getElementById('saveModal').classList.add('hidden');
        document.getElementById('saveModal').classList.remove('flex');
    }

    function submitEditForm() {
        document.getElementById('editForm').submit(); 
    }
</script>
@endsection