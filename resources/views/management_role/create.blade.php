<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mx-3 mb-4">
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('admin.management_role.index') }}"
                       class="text-gray-500 hover:text-gray-700 transition">
                        <x-heroicon-o-arrow-left class="w-5 h-5" />
                    </a>
                    <h2 class="text-xl font-bold text-gray-800">Tambah Role</h2>
                </div>
                <p class="text-gray-500 text-sm ml-8">Buat role baru untuk pengguna</p>
            </div>

            {{-- Card --}}
            <div class="bg-white rounded-xl shadow p-6">
                <form method="POST" action="{{ route('admin.management_role.store') }}" class="space-y-6">
                    @csrf

                    {{-- Nama Role --}}
                    <div>
                        <label for="name" class="block font-medium text-gray-700">
                            Nama Role <span class="text-red-500 font-bold">*</span>
                        </label>
                        <x-text-input id="name" class="block mt-1 w-full" type="text"
                            name="name" :value="old('name')" required placeholder="Contoh: koordinator_logistik" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        <p class="text-xs text-gray-500 mt-1">Gunakan huruf kecil, tanpa spasi (gunakan underscore).</p>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.management_role.index') }}"
                           class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
