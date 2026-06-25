{{-- resources/views/distribusi_bantuan/pengajuan_barang/create.blade.php --}}

@extends('layouts.app')

@section('content')
{{-- Load Tom Select Assets --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<div class="mx-3 flex justify-between items-center">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Tambah Pengajuan & Barang</h2>
        <p class="text-gray-500 text-sm">Input data pengajuan beserta daftar kebutuhan logistik.</p>
    </div>
    <a href="{{ route('admin.management_distribusi.pengajuan_barang.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm transition font-medium">
        &larr; Batal
    </a>
</div>

<div class="bg-white rounded-2xl p-6 m-3 mt-5 shadow-sm border border-gray-100">

{{-- 
<form action="{{ route('distribusi_bantuan.pengajuan.preview_import') }}" method="POST" enctype="multipart/form-data" class="m-3 p-4 bg-indigo-50 border-2 border-dashed border-indigo-200 rounded-2xl flex justify-between items-center">
    @csrf
    <div>
        <h4 class="font-bold text-indigo-800 text-sm">Upload Excel untuk Preview</h4>
        <p class="text-[11px] text-indigo-500 italic">Verifikasi data sebelum masuk database.</p>
    </div>
    <div class="flex gap-2">
        <input type="file" name="file_excel" required class="text-xs">
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-xs font-bold shadow-md">
            Preview Data
        </button>
    </div>
</form> --}}
{{-- Area Upload Excel dengan Referensi Template --}}
<div class="m-3 p-5 bg-indigo-50 border-2 border-dashed border-indigo-200 rounded-3xl">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="bg-white p-3 rounded-2xl shadow-sm text-indigo-600">
                <x-heroicon-o-document-arrow-up class="w-8 h-8"/>
            </div>
            <div>
                <h4 class="font-bold text-indigo-900 text-base">Import Data via Excel</h4>
                <p class="text-xs text-indigo-500 italic">Belum punya formatnya? <a href="{{ asset('templates/template_pengajuan_barang.xlsx') }}" class="text-indigo-700 font-bold underline hover:text-indigo-900">Unduh Template Contoh di Sini</a></p>
            </div>
        </div>
        
        <form action="{{ route('admin.management_distribusi.pengajuan_barang.preview_import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
            @csrf
            <input type="file" name="file_excel" required class="block w-full text-xs text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-xs file:font-semibold
                file:bg-indigo-100 file:text-indigo-700
                hover:file:bg-indigo-200 cursor-pointer"/>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl text-xs font-bold shadow-lg hover:bg-indigo-700 transition flex items-center gap-2 shrink-0">
                <x-heroicon-o-magnifying-glass-circle class="w-4 h-4"/>
                Preview Data
            </button>
        </form>
    </div>

    {{-- Info Tambahan agar user tidak bingung --}}
    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-2 border-t border-indigo-100 pt-4">
        <div class="flex items-center gap-2 text-[10px] text-indigo-400">
            <x-heroicon-o-check-badge class="w-3 h-3 text-green-500"/> Otomatis Create Desa & Bencana
        </div>
        <div class="flex items-center gap-2 text-[10px] text-indigo-400">
            <x-heroicon-o-check-badge class="w-3 h-3 text-green-500"/> Format Tanggal: YYYY-MM-DD
        </div>
        <div class="flex items-center gap-2 text-[10px] text-indigo-400">
            <x-heroicon-o-check-badge class="w-3 h-3 text-green-500"/> Nama Barang Harus Sesuai DB
        </div>
    </div>
</div>


    <form id="createForm" action="{{ route('admin.management_distribusi.pengajuan_barang.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Searchable Dropdown Bencana --}}
            <div class="md:col-span-2">
                <label class="block font-bold text-gray-700 mb-1 text-sm">Pilih Kejadian Bencana (Searchable) *</label>
                <select name="bencana_id" id="select-bencana" class="w-full" required>
                    <option value="">Cari Lokasi, Desa, atau Kategori Bencana...</option>
                    @foreach($bencana as $b)
                        <option value="{{ $b->id }}" 
                            data-desa="{{ $b->desa->nama_desa ?? 'N/A' }}"
                            data-kecamatan="{{ $b->desa->kecamatan ?? '-' }}"
                            data-kategori="{{ $b->kategoriBencana->nama_kategori ?? 'Bencana' }}"
                            data-waktu="{{ $b->pengaduan ? $b->pengaduan->created_at->format('d M Y, H:i') : 'Tanpa Pengaduan' }}"
                            data-rusak="{{ $b->tingkat_kerusakan }}">
                            {{ $b->desa->nama_desa }} - {{ $b->kategoriBencana->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- <div>
                <label class="block font-bold text-gray-700 mb-1 text-sm">Pegawai Pengaju *</label>
                <select name="pegawai_id" class="w-full border rounded-lg p-2.5 shadow-sm focus:ring-2 focus:ring-indigo-500 outline-none" required>
                    @foreach($pegawai as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_pegawai }}</option>
                    @endforeach
                </select>
            </div> --}}

            <div>
                <label class="block font-bold text-gray-700 mb-1 text-sm">Pegawai Pengaju *</label>
                <select name="pegawai_id" class="w-full border rounded-lg p-2.5 shadow-sm focus:ring-2 focus:ring-indigo-500 outline-none" required>
                    {{-- Opsi kosong sebagai placeholder awal --}}
                    <option value="">-- Pilih Pegawai --</option>
                    
                    @foreach($pegawai as $p)
                        {{-- 🟢 UBAH DI SINI: dari $p->id menjadi $p->id_pegawai --}}
                        <option value="{{ $p->id_pegawai }}">{{ $p->nama_pegawai }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block font-bold text-gray-700 mb-1 text-sm">Tanggal Pengajuan *</label>
                <input type="date" name="tgl_pengajuan" value="{{ date('Y-m-d') }}" class="w-full border rounded-lg p-2.5 shadow-sm focus:ring-2 focus:ring-indigo-500 outline-none" required>
            </div>
        </div>

        {{-- Section Daftar Barang --}}
        <div class="border-t pt-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-indigo-700 flex items-center gap-2">
                    <x-heroicon-o-shopping-cart class="w-5 h-5"/>
                    Rincian Barang Logistik
                </h3>
                <button type="button" id="add-row" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-indigo-700 transition flex items-center gap-2 shadow-md">
                    <x-heroicon-o-plus class="w-4 h-4"/>
                    Tambah Baris
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse" id="barang-table">
                    <thead>
                        <tr class="bg-gray-50 text-left text-gray-600 uppercase text-[10px] font-bold tracking-widest">
                            <th class="p-4 border-b w-1/3">Nama Barang</th>
                            <th class="p-4 border-b">Kategori Penerima</th>
                            <th class="p-4 border-b w-1/6">Jumlah</th>
                            <th class="p-4 border-b text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="barang-body">
                        <tr class="item-row hover:bg-gray-50 transition border-b border-gray-100">
                            <td class="p-4">
                                <select name="barang_id[]" class="w-full border-gray-200 rounded-lg p-2 text-sm focus:ring-indigo-500" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($barang as $item)
                                        {{-- 🟢 UBAH DI SINI: dari $item->id menjadi $item->id_barang --}}
                                        <option value="{{ $item->id_barang }}">{{ $item->nama_barang }} ({{ $item->satuan }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-4">
                                <select name="kategori_penerima[]" class="w-full border-gray-200 rounded-lg p-2 text-sm">
                                    <option value="warga">Warga Terdampak</option>
                                    <option value="pengungsi">Pengungsi</option>
                                    <option value="lansia">Lansia</option>
                                    <option value="anak-anak">Anak-anak / Balita</option>
                                    <option value="relawan">Relawan</option>
                                </select>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <input type="number" name="jumlah[]" class="w-full border-gray-200 rounded-lg p-2 text-center font-bold" min="1" required placeholder="0">
                                </div>
                            </td>
                            <td class="p-4 text-center">
                                <span class="text-gray-300 text-xs font-bold uppercase">Utama</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
            <div>
                <label class="block font-bold text-gray-700 mb-1 text-sm tracking-tight text-indigo-600">Catatan / Keterangan Kebutuhan</label>
                <textarea name="keterangan" rows="3" class="w-full border border-gray-200 rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-inner" placeholder="Contoh: Kebutuhan mendesak untuk warga dusun III..."></textarea>
            </div>
            
            <div class="flex justify-end gap-3 pb-2">
                <input type="hidden" name="status_pengajuan" value="pending">
                <button type="submit" class="w-full md:w-auto px-10 py-4 bg-indigo-600 text-white rounded-xl shadow-lg hover:bg-indigo-700 font-bold transition flex items-center justify-center gap-2">
                    <x-heroicon-o-paper-airplane class="w-5 h-5"/>
                    Kirim & Ajukan Sekarang
                </button>
            </div>
        </div>
    </form>
</div>

{{-- MODAL KONFIRMASI SIMPAN --}}
<div id="saveModal" class="fixed inset-0 backdrop-blur-sm bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 text-center transform transition-all scale-100">
        <div class="bg-indigo-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 text-indigo-600">
            <x-heroicon-o-question-mark-circle class="w-12 h-12"/>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">Konfirmasi Pengajuan?</h3>
        <p class="text-sm text-gray-500 mt-2 leading-relaxed">
            Data akan dikirim ke sistem untuk diverifikasi oleh admin. Pastikan daftar barang dan lokasi bencana sudah sesuai.
        </p>
        <div class="flex flex-col gap-2 mt-8">
            <button type="button" onclick="submitCreateForm()" class="w-full py-3 rounded-xl bg-indigo-600 text-white font-bold shadow-lg hover:bg-indigo-700 transition">Ya, Kirim Sekarang</button>
            <button type="button" onclick="closeSaveModal()" class="w-full py-3 rounded-xl bg-gray-50 text-gray-500 font-bold hover:bg-gray-100 transition">Periksa Kembali</button>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Inisialisasi Tom Select (Fitur Search & Detail Waktu)
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
                                <span class="font-medium text-gray-400">KEK:</span> ${escape(data.kecamatan)}
                            </div>
                            <div class="text-[11px] text-gray-500">
                                <span class="font-medium text-gray-400">KATEGORI:</span> ${escape(data.kategori)}
                            </div>
                        </div>
                        <div class="text-[10px] text-indigo-400 mt-1 italic font-medium tracking-tight">
                             Waktu Pengaduan: ${escape(data.waktu)} WIB
                        </div>
                    </div>`;
            },
            item: function(data, escape) {
                return `<div class="font-bold text-indigo-700">
                            ${escape(data.desa)} - <span class="text-gray-500 font-normal text-xs">${escape(data.kategori)}</span> 
                            <span class="text-[10px] text-indigo-400 ml-2 font-medium bg-indigo-50 px-2 py-0.5 rounded">(${escape(data.waktu)})</span>
                        </div>`;
            }
        }
    });

    // 2. Logika Tambah Baris Barang
    document.getElementById('add-row').addEventListener('click', function() {
        const tbody = document.getElementById('barang-body');
        const firstRow = document.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);

        // Reset inputan baris baru
        newRow.querySelectorAll('input').forEach(input => input.value = '');
        newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

        // Ganti kolom aksi dari "Utama" jadi tombol "Hapus"
        const actionCell = newRow.querySelector('td:last-child');
        actionCell.innerHTML = `
            <button type="button" class="text-red-500 p-2 hover:bg-red-50 rounded-full transition remove-row" title="Hapus Baris">
                <svg class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>`;

        tbody.appendChild(newRow);
    });

    // 3. Logika Hapus Baris
    document.getElementById('barang-body').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                e.target.closest('tr').remove();
            }, 200);
        }
    });

    // 4. Logika Modal Konfirmasi
    const createForm = document.getElementById('createForm');
    const saveModal = document.getElementById('saveModal');

    createForm.addEventListener('submit', function(e) {
        e.preventDefault(); 
        saveModal.classList.remove('hidden');
        saveModal.classList.add('flex');
    });
});

function closeSaveModal() {
    const modal = document.getElementById('saveModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function submitCreateForm() {
    document.getElementById('createForm').submit(); 
}



document.getElementById('uploadExcel').addEventListener('change', function(e) {
    let formData = new FormData();
    formData.append('file_excel', e.target.files[0]);
    formData.append('_token', "{{ csrf_token() }}");

    // Loading State
    const btn = e.target.nextElementSibling;
    btn.innerText = "Memproses...";
    btn.disabled = true;

    fetch("{{ route('admin.management_distribusi.pengajuan_barang.import') }}", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(res => {
        if(res.success) {
            const tbody = document.getElementById('barang-body');
            
            res.data.forEach((item, index) => {
                // 1. Otomatis set Bencana ID & Pegawai ID (Ambil dari baris pertama excel)
                if(index === 0) {
                    // Update TomSelect Bencana
                    const ts = document.getElementById('select-bencana').tomselect;
                    ts.addOption({id: item.bencana_id, desa: item.bencana_label, kategori: '', waktu: '', rusak: ''});
                    ts.setValue(item.bencana_id);
                    
                    // Update Pegawai
                    document.querySelector('select[name="pegawai_id"]').value = item.pegawai_id;
                }

                // 2. Tambah baris barang
                const firstRow = document.querySelector('.item-row');
                const newRow = firstRow.cloneNode(true);
                
                newRow.querySelector('select[name="barang_id[]"]').value = item.barang_id;
                newRow.querySelector('select[name="kategori_penerima[]"]').value = item.kategori_penerima;
                newRow.querySelector('input[name="jumlah[]"]').value = item.jumlah;

                // Tambahkan tombol hapus
                newRow.querySelector('td:last-child').innerHTML = `<button type="button" class="text-red-500 remove-row">Hapus</button>`;
                tbody.appendChild(newRow);
            });

            // Hapus baris kosong pertama jika perlu
            alert("Berhasil memproses data Excel!");
        }else {
        // Tampilkan pesan error asli dari Laravel
        alert("Gagal: " + res.message);
    }
        btn.innerText = "Upload File Excel";
        btn.disabled = false;
    })
    .catch(err => {
        alert("Terjadi kesalahan. Periksa format kolom Excel Bapak.");
        console.error(err);
        btn.innerText = "Upload File Excel";
        btn.disabled = false;
    });
});
</script>
@endsection