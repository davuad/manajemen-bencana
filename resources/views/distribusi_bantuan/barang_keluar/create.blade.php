@extends('layouts.app')

@section('content')
{{-- Load TomSelect Assets --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<div class="mx-3 flex justify-between items-center mb-5">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Catat Pengeluaran Barang</h2>
        <p class="text-sm text-gray-500">Otorisasi distribusi logistik berdasarkan data pengajuan riil.</p>
    </div>
    <a href="{{ route('distribusi_bantuan.barang_keluar.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-300 transition">&larr; Kembali</a>
</div>

<form id="mainForm" action="{{ route('distribusi_bantuan.barang_keluar.store') }}" method="POST">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 m-3 pb-20">
        
        {{-- KOLOM KIRI: REFERENSI & DETAIL --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- 1. Referensi Pengajuan & Detail Kejadian --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <h3 class="font-bold text-indigo-700 border-b pb-3 mb-4 flex items-center gap-2 text-sm uppercase">
                    <x-heroicon-o-document-magnifying-glass class="w-5 h-5"/>
                    Referensi Pengajuan
                </h3>
                <div class="space-y-4">
                    <select name="pengajuan_barang_id" id="select-pengajuan" class="w-full" required>
                        <option value="">Cari Kejadian atau Desa...</option>
                        @foreach($pengajuan as $p)
                            <option value="{{ $p->id }}" 
                                data-desa="{{ $p->bencana->desa->nama_desa ?? 'N/A' }}"
                                data-kategori="{{ $p->bencana->kategoriBencana->nama_kategori ?? 'Bencana' }}"
                                data-waktu="{{ \Carbon\Carbon::parse($p->bencana->tanggal)->format('d/M/Y') }}">
                                [ID: {{ $p->id }}] {{ $p->bencana->desa->nama_desa }} - {{ $p->bencana->kategoriBencana->nama_kategori }}
                            </option>
                        @endforeach
                    </select>

                    {{-- DETAIL KEJADIAN DINAMIS (Style Show/Detail) --}}
                    <div id="info-kejadian" class="hidden space-y-4 bg-gray-50 p-5 rounded-2xl border border-dashed border-gray-200">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Kategori Bencana</label>
                            <p id="det-kategori" class="text-sm font-black text-indigo-900 uppercase leading-tight">-</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase block">Tgl Kejadian</label>
                                <p id="det-tgl" class="text-xs font-bold text-gray-700">-</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase block">Skala Dampak</label>
                                <p id="det-rusak" class="text-xs font-bold text-orange-600 uppercase">-</p>
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase block">Lokasi (Desa/Kec)</label>
                            <p id="det-lokasi" class="text-xs font-bold text-gray-800 uppercase flex items-center gap-1">
                                <x-heroicon-o-map-pin class="w-3 h-3 text-red-500"/>
                                <span id="det-lokasi-text">-</span>
                            </p>
                        </div>

                        <div>
                            <label class="text-[10px] font-bold text-indigo-400 uppercase block">Pegawai Pengaju</label>
                            <p id="det-pengaju" class="text-xs font-bold text-indigo-700">-</p>
                        </div>

                        <div class="bg-red-50 p-3 rounded-xl border border-red-100 text-center shadow-sm">
                            <label class="text-[10px] font-bold text-red-400 uppercase block mb-1">Estimasi Korban</label>
                            <p class="text-2xl font-black text-red-700 leading-none">
                                <span id="det-korban">0</span> 
                                <small class="text-[10px] font-normal tracking-normal uppercase">Jiwa</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Otoritas Gudang --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 border-b pb-3 mb-4 flex items-center gap-2 text-sm uppercase">
                    <x-heroicon-o-shield-check class="w-5 h-5 text-indigo-500"/>
                    Otoritas Logistik
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-widest">Gudang Sumber *</label>
                        <select name="gudang_id" id="select-gudang" class="w-full" required>
                            @foreach($gudang as $g)
                                <option value="{{ $g->id }}" data-alamat="{{ $g->alamat }}" data-ket="{{ $g->keterangan }}">
                                    {{ $g->nama_gudang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-widest">Tgl Keluar *</label>
                            <input type="date" name="tgl_keluar" value="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-xl p-3 text-sm font-bold shadow-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-widest">Status Awal</label>
                            <div class="p-3 bg-blue-50 text-blue-700 rounded-xl text-[10px] font-black border border-blue-100 flex items-center gap-2 uppercase shadow-inner">
                                <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span> DIPROSES
                            </div>
                            <input type="hidden" name="status_proses" value="diproses">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-widest">Petugas Gudang (PJ) *</label>
                        <select name="petugas_gudang_id" id="select-petugas" class="w-full" required>
                            <option value="">Pilih Penanggung Jawab...</option>
                            @foreach($pegawai as $pg)
                                <option value="{{ $pg->id }}" data-jabatan="{{ $pg->jabatan }}">
                                    {{ $pg->nama_pegawai }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: DAFTAR BARANG --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 min-h-[500px]">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-gray-800 uppercase text-xs tracking-widest text-indigo-600">Rincian Barang Logistik</h3>
                    <div id="item-count-badge" class="hidden bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-[10px] font-black uppercase">
                        0 Items
                    </div>
                </div>

                <div id="empty-state" class="flex flex-col items-center justify-center py-24 text-gray-300">
                    <x-heroicon-o-magnifying-glass class="w-16 h-16 mb-4 opacity-20"/>
                    <p class="italic text-sm">Pilih referensi pengajuan di panel kiri untuk memuat detail.</p>
                </div>

                <div id="table-area" class="hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold tracking-widest">
                                    <th class="p-4 text-left border-b">Detail Barang</th>
                                    <th class="p-4 text-center border-b">Stok</th>
                                    <th class="p-4 text-center border-b">Minta</th>
                                    <th class="p-4 text-center border-b w-32 text-indigo-600">Realisasi</th>
                                    <th class="p-4 text-left border-b">Catatan</th>
                                </tr>
                            </thead>
                            <tbody id="barang-list" class="divide-y divide-gray-100 text-gray-700"></tbody>
                        </table>
                    </div>

                    <div class="mt-8 pt-6 border-t">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Instruksi Pengiriman Global</label>
                        <textarea name="catatan" rows="3" class="w-full border-gray-200 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition bg-gray-50/50 shadow-inner" placeholder="Contoh: Barang dikirim menggunakan armada BPBD..."></textarea>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white px-12 py-4 rounded-2xl font-black text-sm shadow-xl hover:bg-indigo-700 transition uppercase tracking-widest flex items-center gap-3">
                            <x-heroicon-o-check-circle class="w-6 h-6"/>
                            Simpan Distribusi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- MODAL KONFIRMASI SIMPAN --}}
<div id="saveModal" class="fixed inset-0 backdrop-blur-sm bg-black/40 hidden items-center justify-center z-50 p-4 text-center">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 transform transition-all scale-100">
        <div class="bg-indigo-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 text-indigo-600">
            <x-heroicon-o-question-mark-circle class="w-12 h-12"/>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 uppercase tracking-tight">Proses Pengeluaran?</h3>
        <p class="text-sm text-gray-500 mt-2 leading-relaxed px-4">
            Data distribusi akan disimpan dengan status <b>DIPROSES</b>. Pastikan stok dan realisasi jumlah sudah diverifikasi.
        </p>
        <div class="flex flex-col gap-2 mt-8">
            <button type="button" onclick="submitMainForm()" class="w-full py-3 rounded-xl bg-indigo-600 text-white font-black shadow-lg hover:bg-indigo-700 transition uppercase tracking-widest text-[10px]">Ya, Simpan Sekarang</button>
            <button type="button" onclick="closeSaveModal()" class="w-full py-3 rounded-xl bg-gray-50 text-gray-500 font-bold hover:bg-gray-100 transition uppercase tracking-widest text-[10px]">Periksa Kembali</button>
        </div>
    </div>
</div>

<script>
    // 1. Dropdown Searchable Styles
    new TomSelect("#select-pengajuan", {
        create: false,
        render: {
            option: function(data, escape) {
                return `<div class="py-2 px-3 border-b border-gray-50">
                            <div class="font-bold text-indigo-700">${escape(data.desa)}</div>
                            <div class="text-[10px] text-gray-500 uppercase">${escape(data.kategori)} | ${escape(data.waktu)}</div>
                        </div>`;
            },
            item: function(data, escape) {
                return `<div class="font-bold text-indigo-700">#${escape(data.value)} - ${escape(data.desa)}</div>`;
            }
        }
    });

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

    // 2. AJAX & Dynamic Data Logic
    const selectEl = document.getElementById('select-pengajuan');
    const infoPanel = document.getElementById('info-kejadian');

    selectEl.addEventListener('change', async function() {
        const id = this.value;
        if (!id) { hideView(); return; }

        try {
            const response = await fetch(`/distribusi_bantuan/pengajuan/detail-json/${id}`);
            const data = await response.json();
            
            // ISI SEMUA DETAIL DI PANEL KIRI
            document.getElementById('det-kategori').innerText = data.bencana.kategori_bencana.nama_kategori;
            document.getElementById('det-tgl').innerText = formatDate(data.bencana.tanggal);
            document.getElementById('det-rusak').innerText = data.bencana.tingkat_kerusakan;
            document.getElementById('det-lokasi-text').innerText = `${data.bencana.desa.nama_desa}, Kec. ${data.bencana.desa.kecamatan}`;
            document.getElementById('det-pengaju').innerText = data.pegawai.nama_pegawai;
            document.getElementById('det-korban').innerText = data.bencana.jumlah_korban;

            const details = data.detail_pengajuan || data.detailPengajuan || [];
            renderTable(details);
            
            infoPanel.classList.remove('hidden');
            document.getElementById('table-area').classList.remove('hidden');
            document.getElementById('empty-state').classList.add('hidden');
            document.getElementById('item-count-badge').classList.remove('hidden');
            document.getElementById('item-count-badge').innerText = `${details.length} ITEMS`;

        } catch (e) {
            console.error(e);
            alert('Gagal mengambil detail data.');
        }
    });

    function renderTable(items) {
        const list = document.getElementById('barang-list');
        list.innerHTML = '';
        items.forEach(item => {
            const b = item.barang || {};
            const row = `
                <tr class="hover:bg-indigo-50/20 transition">
                    <td class="p-4">
                        <div class="font-bold text-gray-800">${b.nama_barang || 'Err'}</div>
                        <div class="text-[9px] text-indigo-500 font-bold uppercase tracking-tighter">Penerima: ${item.kategori_penerima}</div>
                        <input type="hidden" name="barang_id[]" value="${item.barang_id}">
                    </td>
                    <td class="p-4 text-center">
                        <span class="px-2 py-1 bg-amber-50 text-amber-700 rounded-lg font-bold text-xs border border-amber-100">${b.stok || 0}</span>
                        <div class="text-[9px] text-gray-400 font-bold uppercase mt-1">${b.satuan || 'Unit'}</div>
                    </td>
                    <td class="p-4 text-center">
                        <span class="font-bold text-gray-700">${item.jumlah}</span>
                        <div class="text-[9px] text-gray-400 uppercase font-bold mt-1">${b.satuan || 'Unit'}</div>
                        <input type="hidden" name="jumlah[]" value="${item.jumlah}">
                    </td>
                    <td class="p-4">
                        <div class="flex flex-col items-center gap-1">
                            <input type="number" name="jumlah_keluar[]" value="${item.jumlah}" min="0" max="${b.stok}" 
                                class="w-full border-2 border-indigo-100 rounded-xl p-2 text-center font-black text-indigo-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition bg-indigo-50/30">
                            <span class="text-[9px] text-indigo-400 font-bold uppercase tracking-tighter">${b.satuan || 'Unit'}</span>
                        </div>
                    </td>
                    <td class="p-4">
                        <input type="text" name="catatan_barang[]" placeholder="..." class="w-full border-b border-gray-100 text-xs py-1 focus:border-indigo-400 outline-none bg-transparent">
                    </td>
                </tr>
            `;
            list.insertAdjacentHTML('beforeend', row);
        });
    }

    function hideView() {
        infoPanel.classList.add('hidden');
        document.getElementById('table-area').classList.add('hidden');
        document.getElementById('empty-state').classList.remove('hidden');
        document.getElementById('item-count-badge').classList.add('hidden');
    }

    function formatDate(dateString) {
        const d = new Date(dateString);
        return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    // 3. Logic Modal Konfirmasi
    const mainForm = document.getElementById('mainForm');
    const saveModal = document.getElementById('saveModal');

    mainForm.addEventListener('submit', function(e) {
        e.preventDefault(); 
        saveModal.classList.remove('hidden');
        saveModal.classList.add('flex');
    });

    function closeSaveModal() {
        saveModal.classList.add('hidden');
        saveModal.classList.remove('flex');
    }

    function submitMainForm() {
        mainForm.submit(); 
    }
</script>
@endsection