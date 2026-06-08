@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <div>
            <h2 class="text-xl font-bold">Olah Data Pegawai</h2>
            <p class="text-gray-500 text-sm">
                Kelola data pegawai untuk kebutuhan administrasi
            </p>
        </div>

        <a href="{{ url('/pegawai/create') }}"
           class="bg-indigo-700 text-white px-4 py-2 rounded-lg inline-block">
            + Tambah Pegawai
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
                    <th class="text-center">Nama Pegawai</th>
                    <th class="text-center">Jabatan</th>
                    <th class="text-center">No HP</th>
                    <th class="text-center">Alamat</th>
                    <th class="text-center">Status</th>
                    <th class="text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($pegawai as $key => $p)
                <tr class="border-t">
                    <td class="p-2 text-center">{{ $key + 1 }}</td>
                    <td class="p-2 text-center">{{ $p->nama_pegawai }}</td>
                    <td class="p-2 text-center">{{ $p->jabatan }}</td>
                    <td class="p-2 text-center">{{ $p->no_hp }}</td>
                    <td class="p-2 text-center">{{ $p->alamat }}</td>

                    <td class="p-2 text-center">
                        @if($p->status_aktif)
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">
                                Aktif
                            </span>
                        @else
                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">
                                Tidak Aktif
                            </span>
                        @endif
                    </td>

                    <td class="flex gap-1 py-3">
                        <a href="{{ url('/pegawai/'.$p->id_pegawai.'/edit') }}"
                           class="text-blue-500 hover:text-blue-700">
                            <x-heroicon-o-pencil-square class="w-5 h-5" />
                        </a>

                        <button
                            onclick="openModal('{{ $p->id_pegawai }}', '{{ $p->nama_pegawai }}')"
                            class="text-red-500 hover:text-red-700">
                            <x-heroicon-o-trash class="w-5 h-5" />
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center p-4">
                        Data pegawai belum ada
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>

<!-- MODAL HAPUS -->
<div id="deleteModal" class="fixed inset-0 backdrop-blur-sm bg-white/10 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">

        <div class="flex items-start gap-3">
            <div class="bg-red-100 p-2 rounded-full">
                <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-500"/>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-gray-800">Hapus Data Pegawai</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Yakin ingin menghapus data pegawai
                    <span id="namaPegawai" class="font-semibold"></span>?
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
function openModal(id, nama) {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    document.getElementById('namaPegawai').innerText = `"${nama}"`;

    let url = "{{ url('/pegawai/:id') }}";
    url = url.replace(':id', id);

    document.getElementById('deleteForm').action = url;
}

function closeModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>

@endsection