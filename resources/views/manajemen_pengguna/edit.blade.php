@extends('layouts.app')

@section('content')
    <div class="mx-3">
        <h2 class="text-xl font-bold">Edit Data Posko</h2>
        <p class="text-gray-500 text-sm">
           Perbarui data posko untuk memastikan informasi tetap akurat
        </p>
    </div>

    <div class="bg-white rounded-xl p-5 m-3 mt-5">
        <form action="{{ route('admin.manajemen_user.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Nama & Foto -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:col-span-2">
                
                <!-- Nama Lengkap -->
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
                        :value="old('nama', $user->nama)" 
                        required 
                        autofocus 
                        placeholder="Contoh: Ahmad Rizqi"
                    />
                    <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                </div>

                <!-- Upload Foto -->
                <div>
                    <label for="foto" class="block font-medium text-gray-700">
                        {{ __('Foto Profil (Opsional)') }}
                    </label>
                    <input 
                        id="foto" 
                        type="file" 
                        name="foto" 
                        accept="image/*"
                        class="block mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maks: 2MB</p>
                    
                    @if($user->foto)
                        <div class="mt-3">
                            <p class="text-sm text-gray-600 mb-2">Foto saat ini:</p>
                            <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto {{ $user->nama }}" class="w-24 h-24 rounded-lg object-cover">
                        </div>
                    @endif
                    
                    <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                </div>

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
                    :value="old('email', $user->email)" 
                    required 
                    placeholder="contoh@email.com"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password (Opsional) -->
            <div>
                <label for="password" class="block font-medium text-gray-700">
                    {{ __('Password (Kosongkan jika tidak ingin mengubah)') }}
                </label>
                <x-text-input 
                    id="password" 
                    class="block mt-1 w-full" 
                    type="password" 
                    name="password" 
                    placeholder="Minimal 8 karakter"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block font-medium text-gray-700">
                    {{ __('Konfirmasi Password') }}
                </label>
                <x-text-input 
                    id="password_confirmation" 
                    class="block mt-1 w-full" 
                    type="password" 
                    name="password_confirmation" 
                    placeholder="Ulangi password"
                />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- NIK -->
            <div>
                <label for="nik" class="block font-medium text-gray-700">
                    {{ __('NIK (Nomor Induk Kependudukan)') }}
                </label>
                <x-text-input 
                    id="nik" 
                    class="block mt-1 w-full" 
                    type="text" 
                    name="nik" 
                    :value="old('nik', $user->nik)" 
                    maxlength="16"
                    placeholder="Contoh: 1234567890123456"
                    autocomplete="off"
                    inputmode="numeric"
                    @input="$el.value = $el.value.replace(/[^0-9]/g, '')"
                />
                <x-input-error :messages="$errors->get('nik')" class="mt-2" />
            </div>

            <!-- No. WA -->
            <div>
                <label for="no_wa" class="block font-medium text-gray-700">
                    {{ __('Nomor WhatsApp') }}
                </label>
                <x-text-input 
                    id="no_wa" 
                    class="block mt-1 w-full" 
                    type="text" 
                    name="no_wa" 
                    :value="old('no_wa', $user->no_wa)" 
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
                <label for="alamat" class="block font-medium text-gray-700">
                    {{ __('Alamat Lengkap') }}
                </label>
                <textarea 
                    id="alamat"
                    name="alamat"
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    rows="3"
                    placeholder="Masukkan alamat lengkap..."
                >{{ old('alamat', $user->alamat) }}</textarea>
                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
            </div>

            <!-- Role -->
           <div>
                <label for="role" class="block font-medium text-gray-700">
                    {{ __('Role') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>

                @if($userRole && $userRole->name === 'admin')
                    <!-- Jika admin, tampilkan read-only -->
                    <div class="block mt-1 w-full border border-gray-300 rounded-md shadow-sm p-3 bg-gray-100">
                        <span class="text-gray-700 font-semibold">
                            {{ ucfirst($userRole->name) }}
                        </span>
                    </div>
                    <!-- Hidden input agar tetap dikirim ke backend -->
                    <input type="hidden" name="role" value="{{ $userRole->name }}">
                @else
                    <!-- Jika bukan admin, tampilkan select normal -->
                    <select 
                        id="role" 
                        name="role" 
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                        required
                    >
                        <option value="">-- Pilih Role --</option>
                        <option value="relawan" {{ old('role', $userRole->name ?? '') === 'relawan' ? 'selected' : '' }}>Relawan</option>
                        <option value="kadus" {{ old('role', $userRole->name ?? '') === 'kadus' ? 'selected' : '' }}>Kadus</option>
                        <option value="kabid" {{ old('role', $userRole->name ?? '') === 'kabid' ? 'selected' : '' }}>Kabid</option>
                        <option value="desa" {{ old('role', $userRole->name ?? '') === 'desa' ? 'selected' : '' }}>Desa</option>
                        <option value="ketua_tim" {{ old('role', $userRole->name ?? '') === 'ketua_tim' ? 'selected' : '' }}>Ketua Tim</option>
                    </select>
                @endif

                <x-input-error :messages="$errors->get('role')" class="mt-2" />
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

            <!-- Status -->
            <div>
                <label class="block font-medium text-gray-700">
                    {{ __('Status') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>
                <div class="flex gap-3 mt-4">
                    
                    <!-- Aktif -->
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="aktif" class="hidden peer"
                            {{ old('status', $user->status) == 'aktif' ? 'checked' : '' }}>
                        
                        <span class="px-6 py-3 rounded-full font-semibold transition-all duration-200
                            peer-checked:bg-green-400 peer-checked:text-green-900
                            bg-green-100 text-green-400 opacity-60">
                            Aktif
                        </span>
                    </label>

                    <!-- Nonaktif -->
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="nonaktif" class="hidden peer"
                            {{ old('status', $user->status) == 'nonaktif' ? 'checked' : '' }}>
                        
                        <span class="px-6 py-3 rounded-full font-semibold transition-all duration-200
                            peer-checked:bg-red-200 peer-checked:text-red-700
                            bg-red-100 text-red-400 opacity-60">
                            Nonaktif
                        </span>
                    </label>

                </div>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>

            </div>

            <!-- Button -->
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.manajemen_user.index') }}"
                class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-semibold">
                    Batal
                </a>

                <button type="submit" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 font-semibold">
                    Update Data
                </button>
            </div>
        </form>
    </div> 
@endsection