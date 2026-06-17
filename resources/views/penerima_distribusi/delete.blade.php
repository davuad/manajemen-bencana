@extends('layouts.app')

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold text-red-600">Hapus Data Penerima</h2>
        <p class="text-gray-500 text-sm">
            Apakah Anda yakin ingin menghapus data penerima berikut?
        </p>
    </div>

    <div class="bg-white rounded-xl p-5 m-3 mt-5">
        <form action="{{ route('penerima.destroy', $item->penerima_id) }}" method="POST">
    @csrf
    @method('DELETE')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block font-medium">Detail Distribusi ID</label>
                    <input type="text"
                        value="{{ $penerima->detail_distribusi_id }}"
                        class="w-full border rounded-lg p-3 bg-gray-100"
                        disabled>
                </div>

                <div>
                    <label class="block font-medium">Nama Penerima</label>
                    <input type="text"
                        value="{{ $penerima->nama_penerima }}"
                        class="w-full border rounded-lg p-3 bg-gray-100"
                        disabled>
                </div>

                <div>
                    <label class="block font-medium">Jabatan</label>
                    <input type="text"
                        value="{{ $penerima->jabatan }}"
                        class="w-full border rounded-lg p-3 bg-gray-100"
                        disabled>
                </div>

                <div>
                    <label class="block font-medium">Instansi</label>
                    <input type="text"
                        value="{{ $penerima->instansi }}"
                        class="w-full border rounded-lg p-3 bg-gray-100"
                        disabled>
                </div>

                <div class="md:col-span-2">
                    <label class="block font-medium">Alamat</label>
                    <input type="text"
                        value="{{ $penerima->alamat }}"
                        class="w-full border rounded-lg p-3 bg-gray-100"
                        disabled>
                </div>

                <div>
                    <label class="block font-medium">Posko</label>

                    @php
                        $posko = [
                            1 => 'Posko A',
                            2 => 'Posko B',
                            3 => 'Posko C'
                        ];
                    @endphp

                    <input type="text"
                        value="{{ $posko[$penerima->nama_posko] ?? '-' }}"
                        class="w-full border rounded-lg p-3 bg-gray-100"
                        disabled>
                </div>

                <div>
                    <label class="block font-medium">No HP</label>
                    <input type="text"
                        value="{{ $penerima->no_hp }}"
                        class="w-full border rounded-lg p-3 bg-gray-100"
                        disabled>
                </div>

                <div>
                    <label class="block font-medium">Status</label>
                    <input type="text"
                        value="{{ $penerima->status }}"
                        class="w-full border rounded-lg p-3 bg-gray-100"
                        disabled>
                </div>

            </div>

            <!-- Button -->
            <div class="flex justify-end gap-3 mt-6">
                <a href="/penerima" class="px-4 py-2 bg-gray-300 rounded-lg">
                    Batal
                </a>

                <button type="submit"
                    class="px-6 py-2 bg-red-600 text-white rounded-lg">
                    Hapus Data
                </button>
            </div>
        </form>
    </div>
@endsection