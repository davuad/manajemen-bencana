<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow p-6">

                {{-- HEADER --}}
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Manajemen Role</h2>
                        <p class="text-gray-500 text-sm">Kelola role dan permission pengguna</p>
                    </div>
                    <a href="#"
                       class="bg-indigo-700 text-white px-4 py-2 rounded-lg inline-block hover:bg-indigo-800 transition">
                        + Tambah Role
                    </a>
                </div>

                {{-- TABLE --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-center w-12">No</th>
                                <th class="p-3 text-left">Nama Role</th>
                                <th class="p-3 text-center">Permission</th>
                                <th class="p-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $key => $role)
                                @php
                                    $permCount = $role->permissions->count();
                                @endphp
                                <tr class="border-t">
                                    <td class="p-2 pl-6 text-center">{{ $key + 1 }}</td>
                                    <td class="p-2 pl-4">
                                        <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-semibold">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    </td>
                                    <td class="p-2 text-center">
                                        <span class="inline-block px-2 py-1 rounded
                                            {{ $permCount > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}
                                            text-xs font-semibold">
                                            {{ $permCount }}/{{ $totalPermissions }}
                                        </span>
                                    </td>
                                    <td class="p-2">
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.management_role.edit', $role->name) }}"
                                               class="text-yellow-500 hover:text-yellow-700 transition"
                                               title="Edit Permission">
                                                <x-heroicon-o-pencil class="w-5 h-5" />
                                            </a>
                                            @if($role->name !== 'admin')
                                                <button class="text-red-500 hover:text-red-700 transition"
                                                        title="Hapus Role">
                                                    <x-heroicon-o-trash class="w-5 h-5" />
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
