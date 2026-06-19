<div class="p-6 bg-white shadow-sm rounded-lg">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Dashboard Kadus</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-blue-50 p-4 rounded-lg">
            <p class="text-sm text-blue-600 font-medium">Total Warga Terdampak</p>
            <p class="text-2xl font-bold text-blue-800">{{ $total_warga ?? 0 }}</p>
        </div>

        <div class="bg-yellow-50 p-4 rounded-lg">
            <p class="text-sm text-yellow-600 font-medium">Warga Belum Diproses</p>
            <p class="text-2xl font-bold text-yellow-800">{{ $warga_pending ?? 0 }}</p>
        </div>

        <div class="bg-green-50 p-4 rounded-lg">
            <p class="text-sm text-green-600 font-medium">Pengaduan Desa</p>
            <p class="text-2xl font-bold text-green-800">{{ $pengaduan_desa ?? 0 }}</p>
        </div>
    </div>
</div>