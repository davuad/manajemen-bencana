<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="nama" :value="__('Nama')" />
            <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama" :value="old('nama')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
        </div>

       <!-- Role Selection (Dynamic) -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Role')" />
            <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                 <option value="">-- Pilih Role --</option>
                <option value="relawan" {{ old('role') === 'relawan' ? 'selected' : '' }}>Relawan</option>
                <option value="kadus" {{ old('role') === 'kadus' ? 'selected' : '' }}>Kadus</option>
                <option value="kabid" {{ old('role') === 'kabid' ? 'selected' : '' }}>Kabid</option>
                <option value="desa" {{ old('role') === 'desa' ? 'selected' : '' }}>Desa</option>
                <option value="ketua_tim" {{ old('role') === 'ketua_tim' ? 'selected' : '' }}>Ketua Tim</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="mt-4" x-data="{ nikLength: 0 }">
            <x-input-label for="nik" :value="__('NIK (Nomor Induk Kependudukan)')" />
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

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
