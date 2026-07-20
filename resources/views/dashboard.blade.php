<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @can('manajemen user')
                        <x-dashboard.admin
                            :total_pengaduan="$total_pengaduan"
                            :pengaduan_pending="$pengaduan_pending"
                            :total_posko="$total_posko"
                            :total_gudang="$total_gudang"
                            :total_bencana="$total_bencana"
                            :total_warga_terdampak="$total_warga_terdampak"
                        />
                    @endcan

                    @can('buat pengaduan')
                        @if(auth()->user()->hasRole('relawan'))
                            <x-dashboard.relawan
                                :pengaduan_saya="$pengaduan_saya"
                                :pengaduan_pending="$pengaduan_pending"
                                :pengaduan_proses="$pengaduan_proses"
                            />
                        @elseif(auth()->user()->hasRole('kadus'))
                            <x-dashboard.kadus
                                :total_warga="$total_warga"
                                :warga_pending="$warga_pending"
                                :pengaduan_desa="$pengaduan_desa"
                            />
                        @elseif(auth()->user()->hasRole('desa'))
                            <x-dashboard.desa
                                :warga_terdampak="$warga_terdampak"
                                :pengaduan_desa="$pengaduan_desa"
                            />
                        @endif
                    @endcan

                    @can('manajemen pengaduan')
                        @if(auth()->user()->hasRole('kabid'))
                            <x-dashboard.kabid
                                :total_bencana="$total_bencana"
                                :bencana_aktif="$bencana_aktif"
                                :total_posko="$total_posko"
                                :total_distribusi="$total_distribusi"
                            />
                        @elseif(auth()->user()->hasRole('ketua_tim'))
                            <x-dashboard.ketua_tim
                                :total_posko="$total_posko"
                                :total_dapur_umum="$total_dapur_umum"
                                :distribusi_pending="$distribusi_pending"
                            />
                        @endif
                    @endcan

                    @can('manajemen distribusi')
                        @if(auth()->user()->hasRole('pegawai'))
                            <x-dashboard.pegawai
                                :total_stok="$total_stok"
                                :distribusi_pending="$distribusi_pending"
                                :gudang_count="$gudang_count"
                                :barang_masuk="$barang_masuk"
                                :barang_keluar="$barang_keluar"
                            />
                        @elseif(auth()->user()->hasRole('petugas'))
                            <x-dashboard.petugas
                                :posko_count="$posko_count"
                                :dapur_umum_count="$dapur_umum_count"
                                :warga_terdampak="$warga_terdampak"
                                :kebutuhan_harian="$kebutuhan_harian"
                            />
                        @endif
                    @endcan

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
