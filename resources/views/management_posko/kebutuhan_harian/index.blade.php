@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">
        {{-- BREADCRUMB --}}
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
            <a href="{{ route('admin.management_posko.dapur_umum.index') }}" class="hover:text-indigo-600">

                Management Posko

            </a>

            <span>/</span>

            <a href="{{ route('admin.management_posko.dapur_umum.index') }}" class="hover:text-indigo-600">

                Dapur Umum

            </a>

            <span>/</span>

            <span class="text-gray-700 font-medium">
                Kebutuhan Harian
            </span>

        </div>
        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">

            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Kebutuhan Harian
                </h2>

                <p class="text-gray-500 text-sm mt-1">
                    Data kebutuhan konsumsi untuk
                    <span class="font-semibold">
                        {{ $dapur->nama_dapur_umum }}
                    </span>
                </p>
            </div>

            {{-- TAMBAH DATA --}}
            <a href="{{ route('admin.management_posko.kebutuhan_harian.create', $dapur->id) }}"
                class="bg-indigo-700 hover:bg-indigo-800 text-white px-4 py-2 rounded-lg inline-block">
                + Tambah Data
            </a>

        </div>


        {{-- FILTER --}}
        <form method="GET" action="{{ route('admin.management_posko.kebutuhan_harian.index', $dapur->id) }}">

            <div class="flex gap-4 mb-6">

                {{-- SEARCH --}}
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari berdasarkan tanggal..."
                    class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">

                {{-- BUTTON FILTER --}}
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">

                    Filter

                </button>

                {{-- RESET --}}
                <a href="{{ route('admin.management_posko.kebutuhan_harian.index', $dapur->id) }}"
                    class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg">

                    Reset

                </a>

            </div>

        </form>


        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full text-sm border-collapse">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="p-3 text-center">
                            No
                        </th>

                        <th class="p-3 text-center">
                            Tanggal
                        </th>

                        <th class="p-3 text-center">
                            Jumlah Warga
                        </th>

                        <th class="p-3 text-center">
                            Porsi / Orang
                        </th>

                        <th class="p-3 text-center">
                            Total Porsi
                        </th>

                        <th class="p-3 text-center">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($kebutuhan as $key => $k)
                        <tr class="border-t hover:bg-gray-50">

                            <td class="p-3 text-center">
                                {{ $kebutuhan->firstItem() + $key }}
                            </td>

                            <td class="p-3 text-center">
                                {{ $k->tanggal }}
                            </td>

                            <td class="p-3 text-center">
                                {{ $k->jumlah_warga }} Orang
                            </td>

                            <td class="p-3 text-center">
                                {{ $k->porsi_per_orang }}
                            </td>

                            <td class="p-3 text-center font-semibold">
                                {{ $k->total_porsi }}
                            </td>

                            <td class="p-3">

                                <div class="flex justify-center gap-3">

                                    {{-- EDIT --}}
                                    <a href="{{ route('admin.management_posko.kebutuhan_harian.edit', $k->id) }}"
                                        class="text-blue-500 hover:text-blue-700">

                                        <x-heroicon-o-pencil-square class="w-5 h-5" />

                                    </a>

                                    {{-- DELETE --}}
                                    <button onclick="openModal('{{ $k->id }}', '{{ $k->tanggal }}')"
                                        class="text-red-500 hover:text-red-700">

                                        <x-heroicon-o-trash class="w-5 h-5" />

                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center p-6 text-gray-500">

                                Data kebutuhan harian belum tersedia

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        <div class="flex justify-between items-center mt-6 text-sm">

            <p class="text-gray-500">

                Menampilkan
                {{ $kebutuhan->firstItem() ?? 0 }}
                -
                {{ $kebutuhan->lastItem() ?? 0 }}

                dari
                {{ $kebutuhan->total() }}
                data

            </p>

            <div>
                {{ $kebutuhan->withQueryString()->links() }}
            </div>

        </div>

    </div>


    {{-- MODAL DELETE --}}
    <div id="deleteModal" class="fixed inset-0 backdrop-blur-sm bg-black/20 hidden items-center justify-center z-50">

        <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">

            {{-- HEADER --}}
            <div class="flex items-start gap-3">

                <div class="bg-red-100 p-2 rounded-full">

                    <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-500" />

                </div>

                <div>

                    <h2 class="text-lg font-semibold text-gray-800">
                        Hapus Data
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">

                        Apakah Anda yakin ingin menghapus data tanggal

                        <span id="namaData" class="font-semibold"></span> ?

                    </p>

                </div>

            </div>

            {{-- ACTION --}}
            <div class="flex justify-end gap-3 mt-6">

                <button onclick="closeModal()" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">

                    Batal

                </button>

                <form id="deleteForm" method="POST">

                    @csrf
                    @method('DELETE')

                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600">

                        Hapus

                    </button>

                </form>

            </div>

        </div>

    </div>


    <script>
        function openModal(id, nama) {
            const modal = document.getElementById('deleteModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.getElementById('namaData').innerText = `"${nama}"`;

            let url = "{{ route('admin.management_posko.kebutuhan_harian.destroy', ':id') }}";

            url = url.replace(':id', id);

            document.getElementById('deleteForm').action = url;
        }

        function closeModal() {
            const modal = document.getElementById('deleteModal');

            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
@endsection
