@extends('layouts.app')

@section('content')
@php
    $prefix = auth()->user()->getRoleNames()->first();
@endphp
<div class="bg-white rounded-xl shadow-lg p-6">

    {{-- Alert --}}
    @if(session('success'))
        <div class="mb-5 rounded-lg bg-green-100 border border-green-300 text-green-700 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">

        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Data Penerima Distribusi
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Kelola seluruh data penerima distribusi bantuan bencana.
            </p>
        </div>

        <a href="{{ route($prefix.'.management_distribusi.penerima.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 transition text-white px-5 py-2 rounded-lg shadow">

            + Tambah Penerima

        </a>

    </div>


    {{-- Search --}}
    <form method="GET">

        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-6">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama, posko, bencana..."
                class="md:col-span-2 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">

        <select
            name="bulan"
            class="border rounded-lg px-3 py-2">

            <option value="">Semua Bulan</option>

            @foreach([
                1=>'Januari',
                2=>'Februari',
                3=>'Maret',
                4=>'April',
                5=>'Mei',
                6=>'Juni',
                7=>'Juli',
                8=>'Agustus',
                9=>'September',
                10=>'Oktober',
                11=>'November',
                12=>'Desember'
            ] as $key => $bulan)

                <option
                    value="{{ $key }}"
                    {{ request('bulan') == $key ? 'selected' : '' }}>

                    {{ $bulan }}

                </option>

            @endforeach

        </select>
        <select
            name="tahun"
            class="border rounded-lg px-3 py-2">

            <option value="">Semua Tahun</option>

            @for($i = date('Y'); $i >= 2025; $i--)

                <option
                    value="{{ $i }}"
                    {{ request('tahun') == $i ? 'selected' : '' }}>

                    {{ $i }}

                </option>

            @endfor

        </select>
            <select
                name="status"
                class="border rounded-lg px-3 py-2">

                <option value="">Semua Status</option>

                <option value="Aktif"
                    {{ request('status')=='Aktif' ? 'selected' : '' }}>
                    Aktif
                </option>

                <option value="Tidak Aktif"
                    {{ request('status')=='Tidak Aktif' ? 'selected' : '' }}>
                    Tidak Aktif
                </option>

            </select>

            <button
                class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">

                Cari

            </button>

        </div>

    </form>


    {{-- Table --}}
    <div class="overflow-x-auto rounded-lg border">

        <table class="w-full text-sm">

            <thead class="bg-slate-100">

                <tr class="text-gray-700">

                    <th class="px-3 py-3 text-center w-16">
                        No
                    </th>

                    <th class="px-3 py-3">
                        Nama Penerima
                    </th>

                    <th class="px-3 py-3">
                        Jabatan
                    </th>

                    <th class="px-3 py-3">
                        Instansi
                    </th>

                    <th class="px-3 py-3">
                        Posko
                    </th>

                    <th class="px-3 py-3">
                        Bencana
                    </th>

                    <th class="px-3 py-3">
                        Alamat
                    </th>

                    <th class="px-3 py-3">
                        No HP
                    </th>

                    <th class="px-3 py-3 text-center">
                        Status
                    </th>

                    <th class="px-3 py-3 text-center">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody>

            @forelse($data as $item)

                <tr class="border-t hover:bg-gray-50 transition">

                    <td class="text-center py-3">

                        {{ $loop->iteration }}

                    </td>

                    <td class="px-3 font-medium text-gray-800">

                        {{ $item->nama_penerima }}

                    </td>

                    <td class="px-3">

                        {{ $item->jabatan }}

                    </td>

                    <td class="px-3">

                        {{ $item->instansi }}

                    </td>

                    <td class="px-3">

                        {{ $item->detailDistribusi?->distribusi?->posko?->nama_posko ?? '-' }}

                    </td>

                    <td class="px-3">

                        {{ $item->detailDistribusi?->distribusi?->bencana?->nama_bencana ?? '-' }}

                    </td>

                    <td class="px-3">

                        {{ $item->alamat }}

                    </td>

                    <td class="px-3 whitespace-nowrap">

                        {{ $item->no_hp }}

                    </td>

                    <td class="text-center">

                        @if($item->status=="Aktif")

                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">

                                Aktif

                            </span>

                        @else

                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">

                                Tidak Aktif

                            </span>

                        @endif

                    </td>

                    <td>

                        <div class="flex justify-center gap-3">

                            <a
                                href="{{ route($prefix.'.management_distribusi.penerima.edit', $item->penerima_id) }}"
                                class="text-blue-600 hover:text-blue-800">

                                <x-heroicon-o-pencil class="w-5 h-5"/>

                            </a>

                            <form
                            action="{{ route('admin.management_distribusi.penerima.destroy', $item->penerima_id) }}"
                            method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus data {{ $item->nama_penerima }}?')">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="text-red-600 hover:text-red-800">

                                <x-heroicon-o-trash class="w-5 h-5"/>

                            </button>

                        </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="10"
                        class="text-center py-10 text-gray-500">

                        @if(request('search'))

                            Data tidak ditemukan.

                        @else

                            Belum ada data penerima distribusi.

                        @endif

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

    </div>

</div>
<script>
const searchInput = document.querySelector('input[name="search"]');

if (searchInput) {

    let timeout = null;

    searchInput.addEventListener('input', function () {

        clearTimeout(timeout);

        if (this.value === '') {
            window.location.href = window.location.pathname;
            return;
        }

        timeout = setTimeout(() => {
            this.form.submit();
        }, 500);

    });

}

function openModal(id, nama)
{
    const modal = document.getElementById('deleteModal');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    document.getElementById('namaData').innerHTML =
        '<span class="text-red-600">"' + nama + '"</span>';

    let url = "{{ route('admin.management_distribusi.penerima.destroy', ':id') }}";
    url = url.replace(':id', id);

    document.getElementById('deleteForm').action = url;
}

function closeModal()
{
    const modal = document.getElementById('deleteModal');

    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

window.onclick = function(event)
{
    const modal = document.getElementById('deleteModal');

    if(event.target === modal)
    {
        closeModal();
    }
}
</script>

@endsection