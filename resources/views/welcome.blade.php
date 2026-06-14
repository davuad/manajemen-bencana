<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Sistem Informasi Manajemen Bencana') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 antialiased font-sans">
    <div
        class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-slate-100 flex flex-col items-center text-center">

        <!-- Logo Dinsos -->
        <img src="{{ asset('logo-dinsos.png') }}" alt="Logo Dinas Sosial Kabupaten Cilacap"
            class="w-24 h-24 mb-6 drop-shadow-md">

        <!-- Title -->
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">
            Sistem Informasi<br>Manajemen Bencana
        </h1>

        <!-- Subtitle -->
        <p class="text-sm text-slate-500 font-semibold mt-2">
            Dinas Sosial Kabupaten Cilacap
        </p>

        <!-- Description -->
        <p class="text-xs text-slate-400 mt-4 px-4 leading-relaxed">
            Platform terintegrasi untuk koordinasi penanganan posko bencana, kebutuhan logistik, pengaduan kebencanaan,
            dan pengelolaan bantuan secara real-time.
        </p>

        <!-- Separator -->
        <div class="w-full border-t border-slate-100 my-8"></div>

        <!-- Navigation Action Buttons -->
        @if (Route::has('login'))
            <div class="w-full space-y-3">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="w-full flex items-center justify-center px-5 py-3 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition rounded-xl shadow-lg shadow-blue-100 hover:shadow-blue-200">
                        Masuk ke Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="w-full flex items-center justify-center px-5 py-3 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition rounded-xl shadow-lg shadow-blue-100 hover:shadow-blue-200">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="w-full flex items-center justify-center px-5 py-3 text-sm font-semibold text-blue-600 bg-slate-50 hover:bg-slate-100 border border-slate-200 hover:border-slate-300 transition rounded-xl">
                            Register
                        </a>
                    @endif
                @endauth
            </div>
        @endif

        <!-- Footer -->
        <p class="text-[10px] text-slate-400 mt-8 font-medium">
            &copy; {{ date('Y') }} Dinas Sosial Kabupaten Cilacap. All rights reserved.
        </p>

    </div>
</body>

</html>
