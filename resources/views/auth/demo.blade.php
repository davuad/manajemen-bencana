<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akun Demo - Sistem Informasi Bencana</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-gray-100 text-gray-900 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Daftar Akun Demo</h1>
            <p class="mt-2 text-sm text-gray-600">Klik tombol untuk menyalin Email atau Password akun testing</p>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Password</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="accounts-table">
                    @php
                        $accounts = [
                            ['role' => 'Admin', 'email' => 'admin@admin.com', 'pass' => 'admin123'],
                            ['role' => 'Relawan', 'email' => 'relawan@test.com', 'pass' => 'password'],
                            ['role' => 'Kadus', 'email' => 'kadus@test.com', 'pass' => 'password'],
                            ['role' => 'Kabid', 'email' => 'kabid@test.com', 'pass' => 'password'],
                            ['role' => 'Desa', 'email' => 'desa@test.com', 'pass' => 'password'],
                            ['role' => 'Ketua Tim', 'email' => 'ketuatim@test.com', 'pass' => 'password'],
                            ['role' => 'Pegawai', 'email' => 'pegawai@test.com', 'pass' => 'password'],
                            ['role' => 'Petugas', 'email' => 'petugas@test.com', 'pass' => 'password'],
                        ];
                    @endphp

                    @foreach ($accounts as $acc)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">{{ $acc['role'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">{{ $acc['email'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">{{ $acc['pass'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <button onclick="copyText('{{ $acc['email'] }}')" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                    Copy Email
                                </button>
                                <button onclick="copyText('{{ $acc['pass'] }}')" class="inline-flex items-center px-2.5 py-1.5 border border-indigo-300 text-xs font-medium rounded text-indigo-700 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                    Copy Pass
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="mt-4 text-center text-xs text-gray-400">Tekan tombol copy lalu paste di kolom input halaman login</p>
    </div>

    <script>
        function copyText(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Teks berhasil disalin: ' + text);
            }).catch(err => {
                console.error('Gagal menyalin: ', err);
            });
        }
    </script>
</body>
</html>
