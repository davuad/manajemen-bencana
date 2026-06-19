{{-- resources/views/distribusi_bantuan/pengajuan_barang/import_preview.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="m-3 flex justify-between items-end">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Verifikasi Kelompok Pengajuan</h2>
        <p class="text-sm text-gray-500">Sistem mengelompokkan data berdasarkan Kejadian Bencana & Pegawai Pengaju.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('distribusi_bantuan.pengajuan.create') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl text-sm font-bold hover:bg-gray-300 transition">
            Batal & Upload Ulang
        </a>
        {{-- Tombol Pemicu Modal --}}
        <button type="button" onclick="openConfirmModal()" class="px-6 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-lg hover:bg-indigo-700 transition flex items-center gap-2">
            <x-heroicon-o-check-circle class="w-5 h-5"/>
            Konfirmasi & Simpan Permanen
        </button>
    </div>
</div>

{{-- Statistik Singkat --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 m-3">
    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
        <p class="text-gray-400 text-[10px] font-bold uppercase">Total Baris Excel</p>
        <p class="text-2xl font-black text-gray-800">{{ count($data) }} <span class="text-sm font-normal text-gray-400">Item</span></p>
    </div>
    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
        <p class="text-gray-400 text-[10px] font-bold uppercase">Calon Pengajuan Baru</p>
        @php $docCount = collect($data)->groupBy(fn($i) => $i['desa_nama'].$i['tanggal'].$i['kategori_nama'].$i['pegawai_id'])->count(); @endphp
        <p class="text-2xl font-black text-indigo-600">
            {{ $docCount }} 
            <span class="text-sm font-normal text-gray-400">Dokumen</span>
        </p>
    </div>
    <div class="bg-white p-4 rounded-xl border border-red-100 shadow-sm">
        <p class="text-red-400 text-[10px] font-bold uppercase">Peringatan</p>
        @php $errorCount = collect($data)->where('barang_exists', false)->count(); @endphp
        <p class="text-2xl font-black {{ $errorCount > 0 ? 'text-red-600' : 'text-green-600' }}">
            {{ $errorCount }} <span class="text-sm font-normal text-gray-400">Error Barang</span>
        </p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm m-3 overflow-hidden border border-gray-100">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold tracking-widest">
            <tr>
                <th class="p-4 text-left">Grup Pengajuan & Lokasi</th>
                <th class="p-4 text-left">Daftar Barang & Kategori Penerima</th>
                <th class="p-4 text-center">Jumlah</th>
                <th class="p-4 text-center">Status Master</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @php
                $grouped = collect($data)->groupBy(function ($item) {
                    return $item['desa_nama'] . '|' . $item['kategori_nama'] . '|' . $item['tanggal'] . '|' . $item['pegawai_id'];
                });
            @endphp

            @foreach($grouped as $key => $items)
                @php $first = $items->first(); @endphp
                <tr class="bg-gray-50/50">
                    <td class="p-4 align-top border-r w-1/3">
                        <div class="flex items-start gap-3">
                            <div class="bg-indigo-100 text-indigo-700 p-2 rounded-lg">
                                <x-heroicon-o-document-text class="w-6 h-6"/>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900 text-base uppercase leading-tight">{{ $first['kategori_nama'] }}</div>
                                <div class="text-gray-500 font-medium italic text-xs mb-2">Desa {{ $first['desa_nama'] }}</div>
                                
                                <div class="space-y-1">
                                    <div class="flex items-center gap-1 text-[10px] text-gray-400">
                                        <x-heroicon-o-calendar class="w-3 h-3"/>
                                        Kejadian: {{ \Carbon\Carbon::parse($first['tanggal'])->format('d M Y') }}
                                    </div>
                                    <div class="flex items-center gap-1 text-[10px] text-gray-400">
                                        <x-heroicon-o-user class="w-3 h-3"/>
                                        Pengaju: <span class="text-indigo-600 font-bold">{{ $first['pegawai_nama'] }}</span>
                                    </div>
                                    <div class="mt-2 pt-2 border-t border-indigo-50">
                                        <div class="flex items-center gap-1 text-[10px] text-red-600 font-bold">
                                            <x-heroicon-o-users class="w-3.5 h-3.5"/>
                                            Total Korban: {{ $first['jumlah_korban'] ?? 0 }} Jiwa
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>

                    <td colspan="2" class="p-0 align-top">
                        <table class="w-full border-collapse">
                            <tbody class="divide-y divide-gray-100">
                                @foreach($items as $subItem)
                                <tr class="{{ !$subItem['barang_exists'] ? 'bg-red-50' : '' }}">
                                    <td class="p-4">
                                        @if($subItem['barang_exists'])
                                            <div class="font-bold text-gray-800">{{ $subItem['barang_nama'] }}</div>
                                        @else
                                            <div class="text-red-600 font-bold flex items-center gap-1">
                                                <x-heroicon-o-x-circle class="w-4 h-4"/>
                                                {{ $subItem['barang_nama'] }} (TIDAK ADA DI DB!)
                                            </div>
                                        @endif
                                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Penerima: {{ $subItem['kategori_penerima'] }}</div>
                                    </td>
                                    <td class="p-4 text-center font-black text-indigo-600 w-24 text-lg">
                                        {{ $subItem['jumlah'] }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>

                    <td class="p-4 text-center align-middle w-32 border-l">
                        @if($first['status_bencana'] == 'Baru')
                            <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-[10px] font-bold block mb-1">DATA BARU</span>
                            <span class="text-[9px] text-gray-400 italic leading-tight block">Sistem otomatis buat master bencana</span>
                        @else
                            <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-[10px] font-bold block mb-1">RE-ORDER</span>
                            <span class="text-[9px] text-gray-400 italic block leading-tight">Gunakan master bencana lama</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($errorCount > 0)
<div class="m-3 bg-red-100 border-l-4 border-red-500 p-4 rounded-r-xl">
    <div class="flex items-center">
        <x-heroicon-o-exclamation-circle class="w-6 h-6 text-red-600 mr-3"/>
        <div>
            <p class="text-red-700 font-bold">Ditemukan Kesalahan Data!</p>
            <p class="text-red-600 text-xs">Ada nama barang yang tidak terdaftar. Tombol simpan dinonaktifkan demi keamanan database.</p>
        </div>
    </div>
</div>
@endif

{{-- MODAL KONFIRMASI SIMPAN --}}
<div id="confirmModal" class="fixed inset-0 backdrop-blur-sm bg-black/30 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 text-center transform transition-all scale-100">
        <div class="bg-indigo-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 text-indigo-600">
            <x-heroicon-o-question-mark-circle class="w-12 h-12"/>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 uppercase">Simpan Import?</h3>
        <p class="text-sm text-gray-500 mt-2 leading-relaxed">
            Sistem akan membuat <b>{{ $docCount }} dokumen pengajuan</b> baru berdasarkan data Excel ini. Tindakan ini tidak dapat dibatalkan.
        </p>

        <form action="{{ route('distribusi_bantuan.pengajuan.store_import') }}" method="POST" id="importForm">
            @csrf
            <div class="flex flex-col gap-2 mt-8">
                <button type="submit" 
                    @if($errorCount > 0) disabled @endif
                    class="w-full py-3 rounded-xl font-bold shadow-lg transition uppercase tracking-widest text-xs
                    {{ $errorCount > 0 ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-indigo-600 text-white hover:bg-indigo-700' }}">
                    @if($errorCount > 0) Perbaiki Error Dahulu @else Ya, Simpan Permanen @endif
                </button>
                <button type="button" onclick="closeConfirmModal()" class="w-full py-3 rounded-xl bg-gray-50 text-gray-500 font-bold hover:bg-gray-100 transition uppercase tracking-widest text-xs">
                    Periksa Kembali
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openConfirmModal() {
        const modal = document.getElementById('confirmModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeConfirmModal() {
        const modal = document.getElementById('confirmModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Menutup modal jika klik di luar box
    window.onclick = function(event) {
        const modal = document.getElementById('confirmModal');
        if (event.target == modal) {
            closeConfirmModal();
        }
    }
</script>
@endsection