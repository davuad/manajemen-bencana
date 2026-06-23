<div class="p-6 bg-white shadow-sm rounded-lg">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Dashboard Kabid</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-blue-50 p-4 rounded-lg">
            <p class="text-sm text-blue-600 font-medium">Total Bencana</p>
            <p class="text-2xl font-bold text-blue-800">{{ $total_bencana ?? 0 }}</p>
        </div>

        <div class="bg-red-50 p-4 rounded-lg">
            <p class="text-sm text-red-600 font-medium">Bencana Berlangsung</p>
            <p class="text-2xl font-bold text-red-800">{{ $bencana_aktif ?? 0 }}</p>
        </div>

        <div class="bg-green-50 p-4 rounded-lg">
            <p class="text-sm text-green-600 font-medium">Total Posko</p>
            <p class="text-2xl font-bold text-green-800">{{ $total_posko ?? 0 }}</p>
        </div>

        <div class="bg-purple-50 p-4 rounded-lg">
            <p class="text-sm text-purple-600 font-medium">Total Distribusi</p>
            <p class="text-2xl font-bold text-purple-800">{{ $total_distribusi ?? 0 }}</p>
        </div>
    </div>
</div>