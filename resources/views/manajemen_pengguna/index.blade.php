@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <div>
            <h2 class="text-xl font-bold">Manajemen Data Pengguna</h2>
            <p class="text-gray-500 text-sm">
                Kelola informasi pengguna sistem
            </p>
        </div>

        <a href="{{ route('admin.manajemen_user.create') }}" class="bg-indigo-700 text-white px-4 py-2 rounded-lg inline-block">
            + Tambah Data Pengguna
        </a>

    </div>

    <div class="flex gap-4 mb-6">

        <form action="{{ route('admin.manajemen_user.index') }}" method="GET" class="flex gap-4 mb-6">
    
            <!-- Search Input -->
            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari berdasarkan Nama atau Email"
                class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">

            <!-- Filter Role -->
            <select name="role" class="border rounded-lg px-4 py-2">
                <option value="">Semua Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>

            <!-- Tombol Filter (Opsional) -->
            <button type="submit" class="bg-indigo-700 text-white px-4 py-2 rounded-lg">
                Cari / Filter
            </button>
            @if(request('search') || request('role') || request('status'))
                <a href="{{ route('admin.manajemen_user.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 underline flex items-center justify-center">
                    Reset
                </a>
            @endif

        </form>

    </div>

    <div class="overflow-x-auto">

       <table class="w-full text-sm">

    <thead class="bg-gray-100">
        <tr>
            <th class="p-3 text-center">No</th>
            <th class="text-center">Foto</th>
            <th class="text-center">Nama</th>
            <th class="text-left pl-4">Email</th>
            <th class="text-center">Deskripsi</th>
            <th class="text-center">No. WA</th>
            <th class="text-left">Role</th>
            <th class="text-center">Status</th>
            <th class="text-left">Aksi</th>
        </tr>
    </thead>

    <tbody>
        @forelse($users as $key => $user)
        <tr class="border-t">
            <td class="p-2 pl-6">{{ $users->firstItem() + $key }}</td>
            <td>
                @if($user->foto)
                    <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto {{ $user->nama }}" class="w-16 h-16 rounded-full object-cover">
                @else
                    <div class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-gray-600">No Photo</span>
                    </div>
                @endif
            </td>
            <td class="p-2 pl-6">{{ $user->nama }}</td>
            <td class="p-2 pl-4">{{ $user->email }}</td>
            <td class="p-2">{{ $user->deskripsi ?? '-' }}</td>
            <td class="p-2 text-center">{{ $user->no_wa ?? '-' }}</td>
            <td class="p-2">
                @forelse($user->roles as $role)
                    <span class="inline-block px-2 py-1 rounded bg-blue-200 text-blue-800 text-xs font-semibold">
                        {{ ucfirst($role->name) }}
                    </span>
                @empty
                    <span class="text-gray-500">-</span>
                @endforelse
            </td>
            <td class="p-2 text-center">
                @if($user->status == 'aktif')
                    <span class="inline-block px-4 py-2 rounded-full bg-green-200 text-green-800 font-semibold">
                        Aktif
                    </span>
                @else
                    <span class="inline-block px-4 py-2 rounded-full bg-red-200 text-red-700 font-semibold opacity-70">
                        Nonaktif
                    </span>
                @endif
            </td>

            <td class="flex gap-1 py-4">
                <button onclick="console.log({{ json_encode($user) }}); openDetailModal({{ json_encode($user) }})" class="text-blue-500 hover:text-blue-700">
                    <x-heroicon-o-eye class="w-5 h-5" />
                </button>

                @if($user->hasRole('admin'))
                    <button disabled class="text-gray-300 cursor-not-allowed" title="Tidak dapat mengedit admin">
                        <x-heroicon-o-pencil-square class="w-5 h-5" />
                    </button>
                @else
                    <a href="{{ route('admin.manajemen_user.edit', $user->id) }}" class="text-yellow-500 hover:text-yellow-700">
                        <x-heroicon-o-pencil-square class="w-5 h-5" />
                    </a>
                @endif

                @if($user->hasRole('admin'))
                    <button disabled class="text-gray-300 cursor-not-allowed" title="Tidak dapat menghapus admin">
                        <x-heroicon-o-trash class="w-5 h-5" />
                    </button>
                @else
                    <button onclick="openModal('{{ $user->id }}', '{{ $user->nama }}')" class="text-red-500 hover:text-red-700">
                        <x-heroicon-o-trash class="w-5 h-5" />
                    </button>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center p-4">
                Data user belum ada
            </td>
        </tr>
        @endforelse

    </tbody>

</table>

    </div>

    <div class="flex justify-between items-center mt-6 text-sm">

        <p class="text-gray-500">
            Menampilkan data pengguna
        </p>

        <div class="flex gap-2">
            <button class="px-3 py-1 border rounded">1</button>
            <button class="px-3 py-1 border rounded">2</button>
        </div>

    </div>

</div>

<!-- MODAL HAPUS -->
<div id="deleteModal" class="fixed inset-0 backdrop-blur-sm bg-white/10 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">

        <!-- Header -->
        <div class="flex items-start gap-3">
            <div class="bg-red-100 p-2 rounded-full">
                <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-500"/>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-gray-800">Hapus Data Pengguna</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Apakah Anda yakin ingin menghapus pengguna 
                    <span id="namaPosko" class="font-semibold"></span>? 
                    Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
        </div>

        <!-- Action -->
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
                    Hapus Data
                </button>
            </form>
        </div>

    </div>
</div>

<!-- Modal Detail User -->
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-96 overflow-y-auto">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Detail Pengguna</h3>
            <button onclick="closeDetailModal()" class="text-gray-500 hover:text-gray-700">
                <x-heroicon-o-x-mark class="w-6 h-6" />
            </button>
        </div>

        <!-- Content -->
        <div class="space-y-4">
            
            <!-- Foto -->
            <div class="flex justify-center mb-4">
                <img id="detailFoto" src="" alt="Foto" class="w-24 h-24 rounded-full object-cover border-2 border-gray-300">
            </div>

            <!-- Nama -->
            <div>
                <label class="block font-semibold text-gray-700">Nama</label>
                <p id="detailNama" class="text-gray-600"></p>
            </div>

            <!-- Email -->
            <div>
                <label class="block font-semibold text-gray-700">Email</label>
                <p id="detailEmail" class="text-gray-600"></p>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block font-semibold text-gray-700">Deskripsi</label>
                <p id="detailDeskripsi" class="text-gray-600"></p>
            </div>

            <!-- NIK -->
            <div>
                <label class="block font-semibold text-gray-700">NIK</label>
                <p id="detailNik" class="text-gray-600"></p>
            </div>

            <!-- No. WA -->
            <div>
                <label class="block font-semibold text-gray-700">No. WhatsApp</label>
                <p id="detailNoWa" class="text-gray-600"></p>
            </div>

            <!-- Alamat -->
            <div>
                <label class="block font-semibold text-gray-700">Alamat</label>
                <p id="detailAlamat" class="text-gray-600"></p>
            </div>

            <!-- Role -->
            <div>
                <label class="block font-semibold text-gray-700">Role</label>
                <p id="detailRole" class="text-gray-600"></p>
            </div>

            <!-- Status -->
            <div>
                <label class="block font-semibold text-gray-700">Status</label>
                <p id="detailStatus"></p>
            </div>

        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 mt-6">
            <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                Tutup
            </button>
        </div>

    </div>
</div>

<!-- JavaScript -->
<script>
    function openDetailModal(user) {
        console.log('User data:', user);
        
        // Set data ke modal
        document.getElementById('detailNama').textContent = user.nama || '-';
        document.getElementById('detailEmail').textContent = user.email || '-';
        document.getElementById('detailDeskripsi').textContent = user.deskripsi || '-';
        document.getElementById('detailNik').textContent = user.nik || '-';
        document.getElementById('detailNoWa').textContent = user.no_wa || '-';
        document.getElementById('detailAlamat').textContent = user.alamat || '-';
        document.getElementById('detailStatus').innerHTML = user.status === 'aktif' 
            ? '<span class="inline-block px-4 py-2 rounded-full bg-green-200 text-green-800 font-semibold">Aktif</span>'
            : '<span class="inline-block px-4 py-2 rounded-full bg-red-200 text-red-700 font-semibold opacity-70">Nonaktif</span>';
        
        // Set foto
        if (user.foto) {
            document.getElementById('detailFoto').src = '/storage/' + user.foto;
        } else {
            document.getElementById('detailFoto').src = 'https://via.placeholder.com/96?text=No+Photo';
        }

        // Set role (dengan pengecekan yang lebih ketat)
        console.log('roles:', user.roles[0].name);
        let roleName = '-';
        if (user.roles && user.roles.length > 0 && user.roles[0] && user.roles[0].name) {
            roleName = user.roles[0].name.charAt(0).toUpperCase() + user.roles[0].name.slice(1);
        }
        document.getElementById('detailRole').textContent = roleName;

        // Buka modal
        const modal = document.getElementById('detailModal');
        modal.classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    // Close modal saat klik di luar
    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetailModal();
        }
    });
</script>

<script>
    function openModal(id, nama) {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.getElementById('namaPosko').innerText = `"${nama}"`;

        // set dynamic route
        document.getElementById('deleteForm').action = `/admin/manajemen_user/${id}`;
    }

    function closeModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection