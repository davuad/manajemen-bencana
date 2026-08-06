{{-- resources/views/distribusi_bantuan/pengajuan_barang/show.blade.php --}}

@extends('layouts.app')

@section('content')
{{-- Pesan Alert Success/Error --}}
@if(session('success'))
    <div class="m-3 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="m-3 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="m-3 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mx-3 flex justify-between items-center">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Detail Pengajuan #{{ $data->id }}</h2>
        <p class="text-gray-500 text-sm">Informasi lengkap terkait permintaan barang logistik</p>
    </div>
    <a href="{{ route('admin.management_distribusi.pengajuan_barang.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm transition font-medium">
        &larr; Kembali ke Daftar
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 m-3 mt-5 pb-24"> 
    
    <div class="lg:col-span-1 space-y-6">
        {{-- Informasi Pengajuan --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <h3 class="font-bold text-indigo-700 border-b pb-3 mb-4 flex items-center gap-2">
                <span><x-heroicon-o-information-circle class="w-5 h-5"/></span>
                Informasi Pengajuan
            </h3>
            
            <div class="space-y-4 text-sm">
                <div>
                    <label class="text-gray-400 block uppercase text-[10px] font-bold">Status Saat Ini</label>
                    <span class="px-2 py-1 rounded text-xs font-bold uppercase 
                        {{ $data->status_pengajuan == 'disetujui' ? 'bg-green-100 text-green-700' : ($data->status_pengajuan == 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ $data->status_pengajuan }}
                    </span>
                </div>

                <div>
                    <label class="text-gray-400 block uppercase text-[10px] font-bold">Pegawai Pengaju</label>
                    <p class="font-semibold text-gray-800">{{ $data->pegawai->nama_pegawai ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="text-gray-400 block uppercase text-[10px] font-bold">Dibuat Oleh (User)</label>
                    <p class="font-semibold text-gray-800 text-indigo-600">{{ $data->creator->nama ?? 'Sistem' }}</p>
                </div>

                <div>
                    <label class="text-gray-400 block uppercase text-[10px] font-bold">Tanggal Pengajuan</label>
                    <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($data->tgl_pengajuan)->format('d F Y') }}</p>
                </div>

                {{-- KETERANGAN DARI PENGAJU --}}
                <div class="pt-3 border-t text-gray-600">
                    <label class="text-gray-400 block uppercase text-[10px] font-bold mb-1">Keterangan Pengaju</label>
                    <p class="leading-relaxed text-xs text-gray-500 italic">
                        "{{ $data->keterangan ?? 'Tidak ada keterangan tambahan.' }}"
                    </p>
                </div>

                {{-- CATATAN DARI ADMIN --}}
                @if($data->status_pengajuan !== 'pending')
                <div class="pt-3 border-t">
                    <label class="text-blue-500 block uppercase text-[10px] font-bold mb-1">Catatan Admin / Alasan</label>
                    <div class="bg-blue-50 p-3 rounded-lg border border-blue-100">
                        <p class="text-xs text-blue-800 font-medium">
                            {{ $data->catatan ?? 'Tidak ada catatan admin.' }}
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Lokasi Bencana --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <h3 class="font-bold text-red-600 border-b pb-3 mb-4 flex items-center gap-2">
                <span><x-heroicon-o-exclamation-triangle class="w-5 h-5"/></span>
                Lokasi & Dampak Bencana
            </h3>
            
            <div class="space-y-4 text-sm">
                <div>
                    <label class="text-gray-400 block uppercase text-[10px] font-bold">Jenis Bencana</label>
                    <p class="font-bold text-gray-800 text-base">
                        {{ $data->bencana->kategoriBencana->nama_kategori ?? 'Kategori N/A' }}
                    </p>
                    <p class="text-xs text-gray-500 italic">Tingkat Kerusakan: {{ $data->bencana->tingkat_kerusakan ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="text-gray-400 block uppercase text-[10px] font-bold">Lokasi (Desa)</label>
                    <p class="font-semibold text-gray-800 flex items-center gap-1">
                        <x-heroicon-o-map-pin class="w-4 h-4 text-gray-400"/>
                        {{ $data->bencana->desa->nama_desa ?? 'Desa Tidak Terdata' }}
                    </p>
                </div>

                <div class="pt-2">
                    <div class="bg-red-50 p-3 rounded-lg border border-red-100 w-full text-center">
                        <label class="text-red-400 block uppercase text-[10px] font-bold">Total Korban</label>
                        <p class="font-bold text-red-700 text-2xl">
                            {{ $data->bencana->jumlah_korban ?? 0 }} 
                            <span class="text-xs font-normal">Orang</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">
        {{-- Daftar Barang --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-800">Daftar Barang yang Diajukan</h3>
                <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-xs font-bold">
                    {{ count($data->detailPengajuan) }} Item
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold">
                            <th class="p-3">Nama Barang</th>
                            <th class="p-3 text-center">Kategori Penerima</th>
                            <th class="p-3 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        {{-- 🟢 PERBAIKAN: Struktur forelse dibersihkan dari token endempty ganda --}}
                        @forelse($data->detailPengajuan as $detail)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3 font-medium text-gray-700">
                                {{ $detail->barang->nama_barang }}
                                <br><small class="text-gray-400 text-[10px]">{{ $detail->barang->satuan }}</small>
                            </td>
                            <td class="p-3 text-center">
                                <span class="bg-gray-100 px-2 py-0.5 rounded text-[10px] text-gray-600 uppercase">
                                    {{ $detail->kategori_penerima }}
                                </span>
                            </td>
                            <td class="p-3 text-right font-bold text-indigo-600">
                                {{ number_format($detail->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-10 text-center text-gray-400 italic">
                                Belum ada daftar barang dalam pengajuan ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Log Verifikasi / Audit Trail --}}
        @if($data->status_pengajuan != 'pending')
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-5">
            <h3 class="font-bold text-blue-800 text-sm mb-3 uppercase tracking-wider">Log Jejak Digital</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div>
                    <label class="text-blue-400 block text-[10px] font-bold uppercase">Diproses & Diverifikasi Oleh</label>
                    <p class="font-bold text-blue-900 text-base">{{ $data->updater->nama ?? 'Administrator' }}</p>
                    <p class="text-[10px] text-blue-400 italic">ID User: #{{ $data->updated_by }}</p>
                </div>
                <div>
                    <label class="text-blue-400 block text-[10px] font-bold uppercase">Waktu Keputusan (WIB)</label>
                    <p class="font-bold text-blue-900 text-base">
                        {{-- 🟢 SINKRONISASI ASIA/JAKARTA (GMT+7) --}}
                        {{ \Carbon\Carbon::parse($data->updated_at)->timezone('Asia/Jakarta')->translatedFormat('d/m/Y, H:i') }} WIB
                    </p>
                    <p class="text-[10px] text-blue-400 italic">Konversi Otomatis Sistem BPBD</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- STICKY ACTION BAR --}}
@if($data->status_pengajuan == 'pending')
<div class="fixed bottom-0 inset-x-0 md:left-64 bg-white border-t shadow-[0_-4px_10px_-2px_rgba(0,0,0,0.1)] p-4 z-40">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-2 text-yellow-700">
            <svg class="w-6 h-6 animate-pulse text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="text-sm font-bold">Verifikasi Diperlukan</p>
                <p class="text-[11px]">Pastikan stok logistik tersedia sebelum menyetujui pengajuan.</p>
            </div>
        </div>
        
        <div class="flex gap-3 w-full md:w-auto">
            {{-- Tombol Tolak --}}
            <button onclick="openDecisionModal('ditolak')" 
                class="flex-1 md:flex-none px-8 py-3 bg-white border border-red-600 text-red-600 rounded-xl text-sm font-bold hover:bg-red-50 transition shadow-sm">
                Tolak Pengajuan
            </button>

            {{-- Tombol ACC --}}
            <button onclick="openDecisionModal('disetujui')" 
                class="flex-1 md:flex-none px-12 py-3 bg-green-600 text-white rounded-xl text-sm font-bold hover:bg-green-700 transition shadow-lg flex items-center justify-center gap-2">
                <x-heroicon-o-check-circle class="w-5 h-5"/>
                Setujui & Teruskan ke Gudang
            </button>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI TINDAKAN --}}
<div id="decisionModal" class="fixed inset-0 backdrop-blur-sm bg-black/30 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 transform transition-all">
        <div class="text-center">
            <div id="modalIcon" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"></div>
            <h3 id="modalTitle" class="text-xl font-bold text-gray-900 mb-2"></h3>
            <p id="modalDescription" class="text-sm text-gray-500 mb-6"></p>
        </div>

        <form action="{{ route('admin.management_distribusi.pengajuan_barang.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            {{-- Hidden Data Dasar --}}
            <input type="hidden" name="bencana_id" value="{{ $data->bencana_id }}">
            <input type="hidden" name="pegawai_id" value="{{ $data->pegawai_id }}">
            <input type="hidden" name="tgl_pengajuan" value="{{ $data->tgl_pengajuan }}">
            <input type="hidden" name="status_pengajuan" id="status_input">
            
            {{-- Data Detail Barang agar tetap utuh di controller --}}
            @foreach($data->detailPengajuan as $detail)
                <input type="hidden" name="barang_id[]" value="{{ $detail->barang_id }}">
                <input type="hidden" name="jumlah[]" value="{{ $detail->jumlah }}">
                <input type="hidden" name="kategori_penerima[]" value="{{ $detail->kategori_penerima }}">
            @endforeach

            <div class="mb-6">
                <label class="block text-left text-sm font-bold text-gray-700 mb-1 italic">Catatan Admin (Alasan ACC/Tolak) *</label>
                <textarea name="catatan" rows="3" class="w-full border rounded-xl p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition" 
                    placeholder="Contoh: Stok di gudang mencukupi, segera didistribusikan..." required></textarea>
            </div>

            <div class="flex justify-end gap-3 border-t pt-4">
                <button type="button" onclick="closeDecisionModal()" class="px-5 py-2 rounded-lg bg-gray-100 text-gray-600 text-sm font-medium">Batal</button>
                <button type="submit" id="submitBtn" class="px-8 py-2 rounded-lg text-white text-sm font-bold shadow-md transition"></button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDecisionModal(status) {
        const modal = document.getElementById('decisionModal');
        const statusInput = document.getElementById('status_input');
        const title = document.getElementById('modalTitle');
        const desc = document.getElementById('modalDescription');
        const icon = document.getElementById('modalIcon');
        const submitBtn = document.getElementById('submitBtn');

        statusInput.value = status;
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        if (status === 'disetujui') {
            title.innerText = 'Setujui Distribusi?';
            desc.innerText = 'Data ini akan otomatis dikirim ke modul Barang Keluar untuk diproses oleh petugas gudang.';
            icon.className = 'w-16 h-16 rounded-full bg-green-100 text-green-600 flex items-center justify-center mx-auto mb-4';
            icon.innerHTML = '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            submitBtn.innerText = 'Ya, Setujui & Kirim';
            submitBtn.className = 'px-8 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-bold shadow-md transition';
        } else {
            title.innerText = 'Tolak Permintaan?';
            desc.innerText = 'Pengajuan akan dihentikan dan data tidak akan diproses lebih lanjut oleh bagian logistik.';
            icon.className = 'w-16 h-16 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4';
            icon.innerHTML = '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            submitBtn.innerText = 'Ya, Tolak';
            submitBtn.className = 'px-8 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-bold shadow-md transition';
        }
    }

    function closeDecisionModal() {
        const modal = document.getElementById('decisionModal');
        const statusInput = document.getElementById('status_input');
        statusInput.value = '';
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endif
@endsection