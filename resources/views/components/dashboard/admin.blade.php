<div class="p-6 bg-white shadow-sm rounded-lg">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Dashboard Admin</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-blue-50 p-4 rounded-lg">
            <p class="text-sm text-blue-600 font-medium">Total Pengaduan</p>
            <p class="text-2xl font-bold text-blue-800">{{ $total_pengaduan ?? 0 }}</p>
        </div>

        <div class="bg-yellow-50 p-4 rounded-lg">
            <p class="text-sm text-yellow-600 font-medium">Pengaduan Pending</p>
            <p class="text-2xl font-bold text-yellow-800">{{ $pengaduan_pending ?? 0 }}</p>
        </div>

        <div class="bg-green-50 p-4 rounded-lg">
            <p class="text-sm text-green-600 font-medium">Total Posko</p>
            <p class="text-2xl font-bold text-green-800">{{ $total_posko ?? 0 }}</p>
        </div>

        <div class="bg-purple-50 p-4 rounded-lg">
            <p class="text-sm text-purple-600 font-medium">Total Gudang</p>
            <p class="text-2xl font-bold text-purple-800">{{ $total_gudang ?? 0 }}</p>
        </div>

        <div class="bg-red-50 p-4 rounded-lg">
            <p class="text-sm text-red-600 font-medium">Total Bencana</p>
            <p class="text-2xl font-bold text-red-800">{{ $total_bencana ?? 0 }}</p>
        </div>

        <div class="bg-indigo-50 p-4 rounded-lg">
            <p class="text-sm text-indigo-600 font-medium">Warga Terdampak</p>
            <p class="text-2xl font-bold text-indigo-800">{{ $total_warga_terdampak ?? 0 }}</p>
        </div>
    </div>
</div>