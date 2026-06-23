<div class="p-6 bg-white shadow-sm rounded-lg">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Dashboard Ketua Tim</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-blue-50 p-4 rounded-lg">
            <p class="text-sm text-blue-600 font-medium">Total Posko</p>
            <p class="text-2xl font-bold text-blue-800">{{ $total_posko ?? 0 }}</p>
        </div>

        <div class="bg-green-50 p-4 rounded-lg">
            <p class="text-sm text-green-600 font-medium">Dapur Umum</p>
            <p class="text-2xl font-bold text-green-800">{{ $total_dapur_umum ?? 0 }}</p>
        </div>

        <div class="bg-yellow-50 p-4 rounded-lg">
            <p class="text-sm text-yellow-600 font-medium">Distribusi Pending</p>
            <p class="text-2xl font-bold text-yellow-800">{{ $distribusi_pending ?? 0 }}</p>
        </div>
    </div>
</div>