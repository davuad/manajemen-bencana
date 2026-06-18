<div class="p-6 bg-white shadow-sm rounded-lg">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Dashboard Pegawai</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-blue-50 p-4 rounded-lg">
            <p class="text-sm text-blue-600 font-medium">Total Stok Gudang</p>
            <p class="text-2xl font-bold text-blue-800">{{ $total_stok ?? 0 }}</p>
        </div>

        <div class="bg-yellow-50 p-4 rounded-lg">
            <p class="text-sm text-yellow-600 font-medium">Distribusi Pending</p>
            <p class="text-2xl font-bold text-yellow-800">{{ $distribusi_pending ?? 0 }}</p>
        </div>

        <div class="bg-green-50 p-4 rounded-lg">
            <p class="text-sm text-green-600 font-medium">Jumlah Gudang</p>
            <p class="text-2xl font-bold text-green-800">{{ $gudang_count ?? 0 }}</p>
        </div>

        <div class="bg-indigo-50 p-4 rounded-lg">
            <p class="text-sm text-indigo-600 font-medium">Barang Masuk Hari Ini</p>
            <p class="text-2xl font-bold text-indigo-800">{{ $barang_masuk ?? 0 }}</p>
        </div>

        <div class="bg-red-50 p-4 rounded-lg">
            <p class="text-sm text-red-600 font-medium">Barang Keluar Hari Ini</p>
            <p class="text-2xl font-bold text-red-800">{{ $barang_keluar ?? 0 }}</p>
        </div>
    </div>
</div>