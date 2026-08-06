@extends('layouts.app')

@section('content')
{{-- Load TomSelect Assets --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

{{-- Notifikasi --}}
@if(session('success'))
    <div class="m-3 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative shadow-sm flex items-center gap-3">
        <x-heroicon-o-check-circle class="w-6 h-6"/>
        <span class="font-bold text-sm">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="m-3 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative shadow-sm flex items-center gap-3">
        <x-heroicon-o-x-circle class="w-6 h-6"/>
        <span class="font-bold text-sm">{{ session('error') }}</span>
    </div>
@endif

<div class="mx-3 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <div class="flex items-center gap-3 flex-wrap">
            <h2 class="text-xl font-bold text-gray-800">Verifikasi Pengeluaran Barang #{{ $data->id }}</h2>
            
            {{-- 🟢 PENAMBAHAN BADGE STATUS VISUAL DINAMIS --}}
            @php
                $statusBadges = [
                    'diproses'   => 'bg-blue-100 text-blue-700 border-blue-200',
                    'dikirim'    => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                    'selesai'    => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'dibatalkan' => 'bg-rose-100 text-rose-700 border-rose-200',
                ];
                $currentBadge = $statusBadges[$data->status_proses] ?? 'bg-gray-100 text-gray-700 border-gray-200';
            @endphp
            <span class="px-3 py-1 border rounded-full text-xs font-black uppercase tracking-wider {{ $currentBadge }}">
                {{ $data->status_proses }}
            </span>
        </div>
        <p class="text-gray-500 text-sm mt-0.5">Otorisasi final pengeluaran logistik dari gudang.</p>
    </div>
    <a href="{{ route('admin.management_distribusi.barang_keluar.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm transition font-medium">
        &larr; Kembali
    </a>
</div>

{{-- 🟢 KONDISI LOCK: Jika status bukan 'diproses' (misal: 'dikirim', 'selesai', 'dibatalkan'), form dikunci --}}
@php $isLocked = $data->status_proses !== 'diproses'; @endphp

<form id="mainForm" action="{{ route('admin.management_distribusi.barang_keluar.update', $data->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 m-3 mt-5 pb-32">
        
        <div class="lg:col-span-1 space-y-6">
            
            {{-- 1. Informasi Bencana --}}
            <div class="bg-red-50 rounded-2xl p-5 shadow-sm border border-red-100">
                <h3 class="font-bold text-red-700 border-b border-red-200 pb-3 mb-4 flex items-center gap-2 uppercase text-sm">
                    <span><x-heroicon-o-exclamation-triangle class="w-5 h-5"/></span>
                    Konteks Kejadian
                </h3>
                
                <div class="space-y-4 text-sm">
                    <div class="bg-white/70 p-3 rounded-xl border border-red-100 shadow-sm">
                        <label class="text-red-400 block uppercase text-[10px] font-bold mb-1">Referensi Dokumen</label>
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('admin.management_distribusi.pengajuan_barang.show', $data->pengajuan_barang_id) }}" target="_blank" class="flex items-center gap-2 bg-indigo-600 text-white px-3 py-2 rounded-lg hover:bg-indigo-700 transition shadow-sm w-fit">
                                <x-heroicon-o-document-magnifying-glass class="w-4 h-4"/>
                                <span class="font-bold text-[10px] uppercase">Lihat Pengajuan #{{ $data->pengajuan_barang_id }}</span>
                            </a>
                            <span class="text-[11px] text-gray-500 font-medium italic">
                                @if($data->pengajuanBarang->bencana->pengaduan)
                                    Laporan: Tiket #{{ $data->pengajuanBarang->bencana->pengaduan_id }} 
                                @else
                                    <span class="text-indigo-600 font-bold flex items-center gap-1"> (Data Diimport)</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="text-red-400 block uppercase text-[10px] font-bold">Kategori Bencana</label>
                        <p class="font-black text-gray-900 text-base leading-tight uppercase">
                            {{ $data->pengajuanBarang->bencana->kategoriBencana->nama_kategori ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-red-400 block uppercase text-[10px] font-bold">Tanggal</label>
                            <p class="font-bold text-gray-700 uppercase text-xs">{{ \Carbon\Carbon::parse($data->pengajuanBarang->bencana->tanggal)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <label class="text-red-400 block uppercase text-[10px] font-bold">Skala</label>
                            <p class="font-bold text-orange-600 text-xs uppercase">{{ $data->pengajuanBarang->bencana->tingkat_kerusakan ?? '-' }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-red-400 block uppercase text-[10px] font-bold">Lokasi (Desa/Kec)</label>
                        <p class="font-bold text-gray-800 uppercase text-xs flex items-center gap-1">
                            <x-heroicon-o-map-pin class="w-4 h-4 text-red-500"/>
                            {{ $data->pengajuanBarang->bencana->desa->nama_desa ?? '-' }}, {{ $data->pengajuanBarang->bencana->desa->kecamatan ?? '-' }}
                        </p>
                    </div>

                    <div class="bg-red-100 p-3 rounded-xl border border-red-200 text-center">
                        <label class="text-red-500 block uppercase text-[10px] font-bold mb-1">Total Korban</label>
                        <p class="font-black text-red-700 text-2xl leading-none">
                            {{ $data->pengajuanBarang->bencana->jumlah_korban ?? 0 }} 
                            <span class="text-xs font-normal">Jiwa</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- 2. Otoritas Gudang --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <h3 class="font-bold text-indigo-700 border-b pb-3 mb-4 flex items-center gap-2 uppercase text-sm">
                    <span><x-heroicon-o-truck class="w-5 h-5"/></span>
                    Logistik Gudang
                </h3>
                
                <div class="space-y-4 text-sm">
                    <div>
                        <label class="text-gray-400 block uppercase text-[10px] font-bold mb-1">Gudang Sumber *</label>
                        @if(!$isLocked)
                            <select name="gudang_id" id="select-gudang" class="w-full">
                                <option value="">-- Pilih Gudang --</option>
                                @foreach($gudangs as $g)
                                    <option value="{{ $g->id }}" {{ $data->gudang_id == $g->id ? 'selected' : '' }} data-alamat="{{ $g->alamat }}">
                                        {{ $g->nama_gudang }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <p class="font-bold text-gray-800 uppercase text-xs">{{ $data->gudang->nama_gudang ?? '-' }}</p>
                                <p class="text-[10px] text-gray-500 mt-1 italic">{{ $data->gudang->alamat ?? 'Alamat tidak tersedia' }}</p>
                            </div>
                            <input type="hidden" name="gudang_id" value="{{ $data->gudang_id }}">
                        @endif
                    </div>

                    <div>
                        <label class="text-gray-400 block uppercase text-[10px] font-bold mb-1">Petugas Gudang (PJ) *</label>
                        @if(!$isLocked)
                            <select name="petugas_gudang_id" id="select-petugas" class="w-full">
                                <option value="">-- Pilih Petugas PJ --</option>
                                @foreach($pegawais as $p)
                                    <option value="{{ $p->id_pegawai }}" {{ $data->petugas_gudang_id == $p->id_pegawai ? 'selected' : '' }} data-jabatan="{{ $p->jabatan }}">
                                        {{ $p->nama_pegawai }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <p class="font-bold text-gray-800 uppercase text-xs">{{ $data->petugasGudang->nama_pegawai ?? '-' }}</p>
                                <p class="text-[10px] text-indigo-600 font-bold mt-1 uppercase">{{ $data->petugasGudang->jabatan ?? '-' }}</p>
                            </div>
                            <input type="hidden" name="petugas_gudang_id" value="{{ $data->petugas_gudang_id }}">
                        @endif
                    </div>
                </div>
            </div>

            {{-- 3. JEJAK DIGITAL (Audit Trail) --}}
            <div class="bg-indigo-900 rounded-2xl p-5 shadow-lg text-white">
                <h3 class="font-bold border-b border-indigo-700 pb-3 mb-4 flex items-center gap-2 uppercase text-xs tracking-widest">
                    <span><x-heroicon-o-finger-print class="w-5 h-5 text-indigo-400"/></span>
                    Jejak Digital
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-indigo-800 p-2 rounded-lg">
                            <x-heroicon-o-user-circle class="w-5 h-5 text-indigo-300"/>
                        </div>
                        <div>
                            <p class="text-[9px] text-indigo-300 uppercase font-bold leading-none mb-1">Terakhir Diperbarui Oleh</p>
                            <p class="text-sm font-bold">{{ $data->updater->nama ?? 'Sistem' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-indigo-800 p-2 rounded-lg">
                            <x-heroicon-o-clock class="w-5 h-5 text-indigo-300"/>
                        </div>
                        <div>
                            <p class="text-[9px] text-indigo-300 uppercase font-bold leading-none mb-1">Waktu Update Terakhir (WIB)</p>
                            {{-- 🟢 SINKRONISASI ASIA/JAKARTA (GMT +7) --}}
                            <p class="text-xs font-medium">{{ \Carbon\Carbon::parse($data->updated_at)->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: TABEL REALISASI --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 min-h-[450px]">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-gray-800 uppercase text-xs tracking-widest text-indigo-600 font-black">Rincian Pengeluaran Barang</h3>
                    <span class="bg-indigo-50 text-indigo-600 px-4 py-1 rounded-full text-[10px] font-black uppercase">
                        {{ count($data->detailBarangKeluar ?? []) }} Item Terdata
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 uppercase text-[10px] font-black tracking-widest">
                                <th class="p-4 text-left border-b">Detail Barang</th>
                                <th class="p-4 text-center border-b">Stok</th>
                                <th class="p-4 text-center border-b">Minta</th>
                                <th class="p-4 text-center border-b w-32 text-indigo-600">Realisasi</th>
                                <th class="p-4 text-left border-b">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($data->detailBarangKeluar ?? [] as $detail)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-4">
                                    <div class="font-bold text-gray-800">{{ $detail->barang->nama_barang }}</div>
                                    <div class="text-[9px] text-indigo-500 font-bold uppercase tracking-tighter mt-0.5">
                                        Penerima: {{ $detail->pengajuanDetail->kategori_penerima ?? 'Warga' }}
                                    </div>
                                    <input type="hidden" name="barang_id[]" value="{{ $detail->barang_id }}">
                                    <input type="hidden" name="jumlah[]" value="{{ $detail->jumlah }}">
                                </td>
                                <td class="p-4 text-center">
                                    <span class="px-2 py-1 bg-amber-50 text-amber-700 rounded-lg font-bold text-xs border border-amber-100">{{ $detail->barang->stok }}</span>
                                    <div class="text-[9px] text-gray-400 font-bold uppercase mt-1">{{ $detail->barang->satuan }}</div>
                                </td>
                                <td class="p-4 text-center text-gray-400 font-black">
                                    {{ $detail->jumlah }}
                                    <div class="text-[8px] uppercase tracking-tighter">{{ $detail->barang->satuan }}</div>
                                </td>
                                <td class="p-4">
                                    @if(!$isLocked)
                                        <div class="flex flex-col items-center gap-1">
                                            <input type="number" name="jumlah_keluar[]" 
                                                   value="{{ $detail->jumlah_keluar ?? $detail->jumlah }}" 
                                                   max="{{ $detail->barang->stok }}" min="0"
                                                   class="w-full border-2 border-indigo-100 rounded-xl p-2 text-center font-black text-indigo-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition shadow-sm bg-indigo-50/30">
                                            <span class="text-[9px] text-indigo-400 font-bold uppercase">{{ $detail->barang->satuan }}</span>
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <div class="font-black text-green-600 text-lg">{{ $detail->jumlah_keluar }}</div>
                                            <div class="text-[9px] text-green-500 font-bold uppercase leading-none">{{ $detail->barang->satuan }}</div>
                                        </div>
                                        <input type="hidden" name="jumlah_keluar[]" value="{{ $detail->jumlah_keluar }}">
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if(!$isLocked)
                                        <input type="text" name="catatan_barang[]" value="{{ $detail->catatan }}" 
                                               placeholder="..."
                                               class="w-full border-b border-gray-100 p-1 text-xs outline-none focus:border-indigo-400 bg-transparent">
                                    @else
                                        <span class="text-xs text-gray-500 italic">{{ $detail->catatan ?? '-' }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <label class="text-gray-400 block uppercase text-[10px] font-bold mb-2 tracking-widest">Catatan Umum Pengiriman</label>
                @if(!$isLocked)
                    <textarea name="catatan" rows="3" class="w-full border border-gray-100 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition bg-gray-50/50 shadow-inner" placeholder="Tulis catatan tambahan di sini...">{{ $data->catatan }}</textarea>
                @else
                    <p class="text-sm text-gray-600 italic bg-gray-50 p-4 rounded-xl border border-dashed">{{ $data->catatan ?? 'Tidak ada catatan tambahan.' }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- STICKY ACTION BAR --}}
    @if(!in_array($data->status_proses, ['selesai', 'dibatalkan']))
    <div class="fixed bottom-0 inset-x-0 md:left-64 bg-white border-t shadow-[0_-10px_25px_-5px_rgba(0,0,0,0.1)] p-5 z-40">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center text-red-600 shadow-inner">
                    <x-heroicon-o-shield-check class="w-7 h-7"/>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900 uppercase tracking-tighter leading-none mb-1">Otorisasi Logistik</p>
                    <p class="text-[10px] text-red-500 font-medium italic">Selesaikan tahapan pengiriman bantuan logistik dinas sosial daerah.</p>
                </div>
            </div>
            
            <div class="flex gap-3 w-full md:w-auto">
                @if($data->status_proses === 'diproses')
                    <button type="button" onclick="openStatusModal('dibatalkan')" class="px-6 py-3 bg-white border-2 border-red-600 text-red-600 rounded-xl text-xs font-bold hover:bg-red-50 transition uppercase tracking-widest">Batalkan</button>
                    <button type="button" onclick="openStatusModal('dikirim')" class="px-8 py-3 bg-yellow-500 text-white rounded-xl text-xs font-bold hover:bg-yellow-600 transition shadow-lg uppercase tracking-widest">Set Dikirim</button>
                @elseif($data->status_proses === 'dikirim')
                    {{-- <button type="button" onclick="openStatusModal('selesai')" class="px-12 py-3 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 shadow-xl transition uppercase tracking-widest w-full md:w-auto">Selesaikan Distribusi</button> --}}
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL KONFIRMASI STATUS --}}
    <div id="statusModal" class="fixed inset-0 backdrop-blur-sm bg-black/40 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 transform transition-all scale-100">
            <div class="text-center mb-8">
                <div id="modalIcon" class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm"></div>
                <h3 id="modalTitle" class="text-2xl font-bold text-gray-900 mb-2 uppercase tracking-tight"></h3>
                <p id="modalDescription" class="text-sm text-gray-500 px-4"></p>
            </div>
            <input type="hidden" name="status_proses" id="status_input">
            <div class="grid grid-cols-2 gap-3 mt-8 border-t pt-6">
                <button type="button" onclick="closeStatusModal()" class="py-3 rounded-2xl bg-gray-100 text-sm font-bold text-gray-600 hover:bg-gray-200 transition uppercase tracking-widest text-[10px]">Batal</button>
                <button type="button" onclick="submitMainForm()" id="submitBtn" class="py-3 rounded-2xl text-white text-sm font-bold shadow-lg transition uppercase tracking-widest text-[10px]"></button>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const selectGudang = document.getElementById('select-gudang');
        if(selectGudang) {
            new TomSelect("#select-gudang", {
                create: false,
                render: {
                    option: function(data, escape) {
                        return `<div class="py-2 px-3 border-b border-gray-50">
                                    <div class="font-bold text-gray-800">${escape(data.text)}</div>
                                    <div class="text-[10px] text-gray-500 italic">${escape(data.alamat)}</div>
                                </div>`;
                    },
                    item: function(data, escape) {
                        return `<div>
                                    <div class="font-bold text-indigo-700 text-xs">${escape(data.text)}</div>
                                    <div class="text-[9px] text-gray-400 font-medium">${escape(data.alamat)}</div>
                                </div>`;
                    }
                }
            });
        }

        const selectPetugas = document.getElementById('select-petugas');
        if(selectPetugas) {
            new TomSelect("#select-petugas", {
                create: false,
                render: {
                    option: function(data, escape) {
                        return `<div class="py-2 px-3 border-b border-gray-50">
                                    <div class="font-bold text-gray-800">${escape(data.text)}</div>
                                    <div class="text-[10px] text-indigo-600 font-bold uppercase">${escape(data.jabatan)}</div>
                                </div>`;
                    },
                    item: function(data, escape) {
                        return `<div>
                                    <div class="font-bold text-indigo-700 text-xs">${escape(data.text)}</div>
                                    <div class="text-[9px] text-indigo-500 font-black uppercase tracking-tighter">${escape(data.jabatan)}</div>
                                </div>`;
                    }
                }
            });
        }
    });

    const mainForm = document.getElementById('mainForm');
    
    mainForm.addEventListener('submit', function(e) {
        const statusInp = document.getElementById('status_input');
        if(!statusInp.value) {
            e.preventDefault();
            openStatusModal('selesai');
        }
    });

    function openStatusModal(status) {
        const modal = document.getElementById('statusModal');
        const statusInput = document.getElementById('status_input');
        const title = document.getElementById('modalTitle');
        const desc = document.getElementById('modalDescription');
        const icon = document.getElementById('modalIcon');
        const submitBtn = document.getElementById('submitBtn');

        statusInput.value = status;
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        if (status === 'selesai') {
            title.innerText = 'Konfirmasi Selesai?';
            desc.innerText = 'Angka realisasi akan dikunci dan stok gudang terpotong permanen.';
            icon.className = 'w-24 h-24 rounded-full bg-green-100 text-green-600 flex items-center justify-center mx-auto mb-6 shadow-inner';
            icon.innerHTML = '<svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>';
            submitBtn.innerText = 'Ya, Selesaikan';
            submitBtn.className = 'py-3 rounded-2xl bg-green-600 hover:bg-green-700 text-white text-xs font-bold shadow-md uppercase tracking-widest w-full';
        } else if (status === 'dikirim') {
            title.innerText = 'Kirim Barang?';
            desc.innerText = 'Status pengiriman berubah menjadi DIKIRIM (Dalam Perjalanan armada BPBD).';
            icon.className = 'w-24 h-24 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mx-auto mb-6 shadow-inner';
            icon.innerHTML = '<svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
            submitBtn.innerText = 'Set Dikirim';
            submitBtn.className = 'py-3 rounded-2xl bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-bold shadow-md uppercase tracking-widest w-full';
        } else {
            title.innerText = 'Batalkan Distribusi?';
            desc.innerText = 'Seluruh proses otorisasi akan dihentikan tanpa ada pemotongan stok komoditas gudang.';
            icon.className = 'w-24 h-24 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-6 shadow-inner';
            icon.innerHTML = '<svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>';
            submitBtn.innerText = 'Ya, Batalkan';
            submitBtn.className = 'py-3 rounded-2xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-md uppercase tracking-widest w-full';
        }
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
        document.getElementById('statusModal').classList.remove('flex');
        document.getElementById('status_input').value = '';
    }

    function submitMainForm() {
        document.getElementById('mainForm').submit(); 
    }
</script>
@endsection