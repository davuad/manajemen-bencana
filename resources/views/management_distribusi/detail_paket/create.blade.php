@extends('layouts.app')

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold">Tambah Detail Paket Bantuan</h2>
        <p class="text-gray-500 text-sm">
            Tambahkan barang ke dalam paket bantuan yang dipilih
        </p>
    </div>

    <div class="bg-white rounded-xl p-5 m-3 mt-5">
        @if ($errors->any())
            <div class="mb-4 p-4 rounded-lg bg-red-100 text-red-800 border border-red-300">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.management_distribusi.detail_paket.store') }}" method="POST" class="space-y-6">
            @csrf

            <input type="hidden" name="paket_bantuan_id" value="{{ $paket_bantuan->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Informasi Paket --}}
                <div>
                    <label class="block font-medium">Nama Paket Bantuan</label>
                    <input type="text" class="w-full border rounded-lg p-3 bg-gray-100"
                        value="{{ $paket_bantuan->nama_paket }}" readonly>
                </div>

                <div>
                    <label class="block font-medium">Posko</label>
                    <input type="text" class="w-full border rounded-lg p-3 bg-gray-100"
                        value="{{ $paket_bantuan->posko->nama_posko ?? '-' }}" readonly>
                </div>

                {{-- Pilih Barang --}}
                <div>
                    <label class="block font-medium">Barang *</label>
                    <select name="barang_id" id="barang_id" class="w-full border rounded-lg p-3" required>
                        <option value="">Pilih Barang</option>
                        @foreach ($stok_barang as $stok)
                            <option value="{{ $stok->barang_id }}" data-satuan="{{ $stok->barang->satuan ?? '' }}"
                                {{ old('barang_id') == $stok->barang_id ? 'selected' : '' }}>
                                {{ $stok->barang->nama_barang ?? '-' }} - stok: {{ $stok->jumlah_barang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Satuan Otomatis --}}
                <div>
                    <label class="block font-medium">Satuan</label>
                    <input type="text" id="satuan" class="w-full border rounded-lg p-3 bg-gray-100" readonly>
                </div>

                {{-- Jumlah --}}
                <div>
                    <label class="block font-medium">Jumlah *</label>
                    <input type="number" name="jumlah" class="w-full border rounded-lg p-3" min="1"
                        value="{{ old('jumlah') }}" placeholder="Masukkan jumlah barang" required>
                </div>

            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.management_distribusi.detail_paket.index', ['paket_bantuan_id' => $paket_bantuan->id]) }}"
                    class="px-4 py-2 bg-gray-300 rounded-lg">
                    Batal
                </a>

                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg">
                    Simpan Detail Paket
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const barangSelect = document.getElementById('barang_id');
            const satuanInput = document.getElementById('satuan');

            function updateSatuan() {
                const selectedOption = barangSelect.options[barangSelect.selectedIndex];
                satuanInput.value = selectedOption.getAttribute('data-satuan') || '';
            }

            updateSatuan();
            barangSelect.addEventListener('change', updateSatuan);
        });
    </script>
@endsection
