@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <div>
            <h2 class="text-xl font-bold">Olah Data Relawan</h2>
            <p class="text-gray-500 text-sm">
                Kelola data relawan PSKS
            </p>
        </div>

        <a href="{{ route('admin.management_pegawai.relawan.create') }}"
           class="bg-indigo-700 text-white px-4 py-2 rounded-lg inline-block">
            + Tambah Relawan
        </a>

    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-center">No</th>
                    <th class="text-center">Foto</th>
                    <th class="text-center">Nama Relawan</th>
                    <th class="text-center">Jenis PSKS</th>
                    <th class="text-center">Kecamatan</th>
                    <th class="text-center">No HP</th>
                    <th class="text-center">Alamat</th>
                    <th class="text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($relawan as $key => $r)

                <tr class="border-t">

                    <td class="p-2 text-center">
                        {{ $key + 1 }}
                    </td>

                    <td class="p-2 text-center">
                        @if($r->foto)
                            <img src="{{ asset('storage/'.$r->foto) }}"
                                 class="w-12 h-12 rounded-full object-cover mx-auto">
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>

                    <td class="p-2 text-center">
                        {{ $r->nama_relawan }}
                    </td>

                    <td class="p-2 text-center">
                        {{ $r->jenis_psks }}
                    </td>

                    <td class="p-2 text-center">
                        {{ $r->kecamatan }}
                    </td>

                    <td class="p-2 text-center">
                        {{ $r->no_hp }}
                    </td>

                    <td class="p-2 text-center">
                        {{ $r->alamat }}
                    </td>

                    <td class="flex gap-2 py-3">

                        <a href="{{ route('admin.management_pegawai.relawan.edit', $r->id_relawan) }}"
                           class="text-blue-500 hover:text-blue-700">
                            <x-heroicon-o-pencil-square class="w-5 h-5" />
                        </a>

                        <button
                            onclick="openModal('{{ $r->id_relawan }}','{{ $r->nama_relawan }}')"
                            class="text-red-500 hover:text-red-700">
                            <x-heroicon-o-trash class="w-5 h-5" />
                        </button>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="8" class="text-center p-4">
                        Data relawan belum ada
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<!-- Modal Hapus -->

<div id="deleteModal"
     class="fixed inset-0 backdrop-blur-sm bg-white/10 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">

        <div class="flex items-start gap-3">

            <div class="bg-red-100 p-2 rounded-full">
                <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-500"/>
            </div>

            <div>

                <h2 class="text-lg font-semibold text-gray-800">
                    Hapus Data Relawan
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Yakin ingin menghapus relawan
                    <span id="namaRelawan" class="font-semibold"></span>?
                </p>

            </div>

        </div>

        <div class="flex justify-end gap-3 mt-6">

            <button onclick="closeModal()"
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">
                Batal
            </button>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600">
                    Hapus
                </button>

            </form>

        </div>

    </div>

</div>

<script>

function openModal(id, nama){

    const modal = document.getElementById('deleteModal');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    document.getElementById('namaRelawan').innerText = `"${nama}"`;

    let url = "/admin/management-pegawai/relawan/:id";

    url = url.replace(':id', id);

    document.getElementById('deleteForm').action = url;
}

function closeModal(){

    const modal = document.getElementById('deleteModal');

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

</script>

@endsection