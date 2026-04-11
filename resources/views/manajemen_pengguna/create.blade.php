@extends('layouts.app')

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold">Tambah Data Pengguna</h2>
        <p class="text-gray-500 text-sm">
           Lengkapi informasi pengguna untuk menambahkan data baru ke dalam sistem. Pastikan semua data yang dimasukkan benar dan sesuai dengan format yang ditentukan.
        </p>
    </div>
    <div class="bg-white rounded-xl p-5 m-3 mt-5">
        <form action="{{ route('admin.manajemen_user.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    
    <!-- Nama -->
            <div>
                <label for="nama" class="block font-medium text-gray-700">
                    {{ __('Nama Lengkap') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>
                <x-text-input 
                    id="nama" 
                    class="block mt-1 w-full" 
                    type="text" 
                    name="nama" 
                    :value="old('nama')" 
                    required 
                    autofocus 
                    placeholder="Contoh: Ahmad Rizqi"
                />
                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
            </div>

            <!-- Upload Foto -->
            <div>
                <x-input-label for="foto" :value="__('Foto Profil (Opsional)')" />
                <input 
                    id="foto" 
                    type="file" 
                    name="foto" 
                    accept="image/*"
                    class="block mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-500 focus:ring-indigo-500"
                />
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maks: 2MB</p>
                <x-input-error :messages="$errors->get('foto')" class="mt-2" />
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block font-medium text-gray-700">
                    {{ __('Email') }}
                    <span class="text-red-500 font-bold">*</span>
                </label> 
                <x-text-input 
                    id="email" 
                    class="block mt-1 w-full" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required 
                    placeholder="contoh@email.com"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block font-medium text-gray-700">
                    {{ __('Password') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>
                <x-text-input 
                    id="password" 
                    class="block mt-1 w-full" 
                    type="password" 
                    name="password" 
                    required 
                    placeholder="Minimal 8 karakter"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

                        <!-- NIK -->
            <div x-data="{ nikLength: 0 }">
                <label for="nik" class="block font-medium text-gray-700">
                    {{ __('NIK (Nomor Induk Kependudukan)') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>
                <x-text-input 
                    id="nik" 
                    class="block mt-1 w-full" 
                    type="text" 
                    name="nik" 
                    :value="old('nik')" 
                    maxlength="16"
                    placeholder="Contoh: 1234567890123456"
                    autocomplete="off"
                    inputmode="numeric"
                    @input="$el.value = $el.value.replace(/[^0-9]/g, ''); nikLength = $el.value.length"
                />
                <p class="text-sm text-gray-500 mt-1">
                <span x-text="nikLength"></span>/16
                </p>
                <x-input-error :messages="$errors->get('nik')" class="mt-2" />
            </div>


            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block font-medium text-gray-700">
                    {{ __('Konfirmasi Password') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>
                <x-text-input 
                    id="password_confirmation" 
                    class="block mt-1 w-full" 
                    type="password" 
                    name="password_confirmation" 
                    required 
                    placeholder="Ulangi password"
                />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>


            <!-- No. WA -->
            <div>
                <x-input-label for="no_wa" :value="__('Nomor WhatsApp')" />
                <x-text-input 
                    id="no_wa" 
                    class="block mt-1 w-full" 
                    type="text" 
                    name="no_wa" 
                    :value="old('no_wa')" 
                    maxlength="15"
                    placeholder="Contoh: 081234567890"
                    autocomplete="off"
                    inputmode="numeric"
                    @input="$el.value = $el.value.replace(/[^0-9]/g, '')"
                />
                <x-input-error :messages="$errors->get('no_wa')" class="mt-2" />
            </div>

            <!-- Alamat -->
            <div class="md:col-span-2">
                <x-input-label for="alamat" :value="__('Alamat Lengkap')" />
                <textarea 
                    id="alamat"
                    name="alamat"
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    rows="3"
                    placeholder="Masukkan alamat lengkap..."
                >{{ old('alamat') }}</textarea>
                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block font-medium text-gray-700">
                    {{ __('Role') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>
                <select 
                    id="role" 
                    name="role" 
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                    required
                >
                    <option value="">-- Pilih Role --</option>
                    <option value="relawan" {{ old('role') === 'relawan' ? 'selected' : '' }}>Relawan</option>
                    <option value="kadus" {{ old('role') === 'kadus' ? 'selected' : '' }}>Kadus</option>
                    <option value="kabid" {{ old('role') === 'kabid' ? 'selected' : '' }}>Kabid</option>
                    <option value="desa" {{ old('role') === 'desa' ? 'selected' : '' }}>Desa</option>
                    <option value="ketua_tim" {{ old('role') === 'ketua_tim' ? 'selected' : '' }}>Ketua Tim</option>
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <!-- Status -->
            <div>
                <x-input-label for="status" :value="__('Status')" />
                <select 
                    id="status" 
                    name="status" 
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                    required
                >
                    <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : 'selected' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>

            <!-- Deskripsi -->
            <div class="md:col-span-2">
                <label for="deskripsi" class="block font-medium text-gray-700">
                    {{ __('Deskripsi / Penjelasan (Opsional)') }}
                </label>
                <textarea 
                    id="deskripsi"
                    name="deskripsi"
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    rows="3"
                    placeholder="Contoh: Ketua Tim Elang, Kadus Pakuwon, dll"
                >{{ old('deskripsi', $user->deskripsi ?? '') }}</textarea>
                <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
            </div>

        </div>

            <!-- Button -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.manajemen_user.index') }}" class="px-4 py-2 bg-gray-300 rounded-lg">
                    Batall
                </a>

                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg">
                    Simpan Data Pengguna
                </button>
            </div>
            </div>
        </form>
@endsection