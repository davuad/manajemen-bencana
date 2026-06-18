<div class="p-6 bg-white shadow-sm rounded-lg">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Dashboard Petugas</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-blue-50 p-4 rounded-lg">
            <p class="text-sm text-blue-600 font-medium">Total Posko</p>
            <p class="text-2xl font-bold text-blue-800">{{ $posko_count ?? 0 }}</p>
        </div>

        <div class="bg-green-50 p-4 rounded-lg">
            <p class="text-sm text-green-600 font-medium">Dapur Umum</p>
            <p class="text-2xl font-bold text-green-800">{{ $dapur_umum_count ?? 0 }}</p>
        </div>

        <div class="bg-red-50 p-4 rounded-lg">
            <p class="text-sm text-red-600 font-medium">Warga Terdampak</p>
            <p class="text-2xl font-bold text-red-800">{{ $warga_terdampak ?? 0 }}</p>
        </div>

        <div class="bg-yellow-50 p-4 rounded-lg">
            <p class="text-sm text-yellow-600 font-medium">Kebutuhan Harian Hari Ini</p>
            <p class="text-2xl font-bold text-yellow-800">{{ $kebutuhan_harian ?? 0 }}</p>
        </div>
    </div>
</div>