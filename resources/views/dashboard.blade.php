<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @role('admin')
                        <x-dashboard.admin
                            :total_pengaduan="$total_pengaduan"
                            :pengaduan_pending="$pengaduan_pending"
                            :total_posko="$total_posko"
                            :total_gudang="$total_gudang"
                            :total_bencana="$total_bencana"
                            :total_warga_terdampak="$total_warga_terdampak"
                        />
                    @elserole('relawan')
                        <x-dashboard.relawan
                            :pengaduan_saya="$pengaduan_saya"
                            :pengaduan_pending="$pengaduan_pending"
                            :pengaduan_proses="$pengaduan_proses"
                        />
                    @elserole('kadus')
                        <x-dashboard.kadus
                            :total_warga="$total_warga"
                            :warga_pending="$warga_pending"
                            :pengaduan_desa="$pengaduan_desa"
                        />
                    @elserole('kabid')
                        <x-dashboard.kabid
                            :total_bencana="$total_bencana"
                            :bencana_aktif="$bencana_aktif"
                            :total_posko="$total_posko"
                            :total_distribusi="$total_distribusi"
                        />
                    @elserole('desa')
                        <x-dashboard.desa
                            :warga_terdampak="$warga_terdampak"
                            :pengaduan_desa="$pengaduan_desa"
                        />
                    @elserole('ketua_tim')
                        <x-dashboard.ketua_tim
                            :total_posko="$total_posko"
                            :total_dapur_umum="$total_dapur_umum"
                            :distribusi_pending="$distribusi_pending"
                        />
                    @elserole('pegawai')
                        <x-dashboard.pegawai
                            :total_stok="$total_stok"
                            :distribusi_pending="$distribusi_pending"
                            :gudang_count="$gudang_count"
                            :barang_masuk="$barang_masuk"
                            :barang_keluar="$barang_keluar"
                        />
                    @elserole('petugas')
                        <x-dashboard.petugas
                            :posko_count="$posko_count"
                            :dapur_umum_count="$dapur_umum_count"
                            :warga_terdampak="$warga_terdampak"
                            :kebutuhan_harian="$kebutuhan_harian"
                        />
                    @else
                        <p class="text-red-500">Role tidak dikenali atau belum diatur.</p>
                    @endrole

                </div>
            </div>
        </div>
    </div>
</x-app-layout>