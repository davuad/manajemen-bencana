<div class="p-6 bg-white shadow-sm rounded-lg">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Dashboard Desa</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-blue-50 p-4 rounded-lg">
            <p class="text-sm text-blue-600 font-medium">Warga Terdampak</p>
            <p class="text-2xl font-bold text-blue-800">{{ $warga_terdampak ?? 0 }}</p>
        </div>

        <div class="bg-green-50 p-4 rounded-lg">
            <p class="text-sm text-green-600 font-medium">Pengaduan Desa</p>
            <p class="text-2xl font-bold text-green-800">{{ $pengaduan_desa ?? 0 }}</p>
        </div>
    </div>
</div>