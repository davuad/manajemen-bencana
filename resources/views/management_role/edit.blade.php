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
                    <h2 class="text-xl font-bold text-gray-800">Edit Permission Role</h2>
                </div>
                <p class="text-gray-500 text-sm ml-8">Atur permission untuk role <span class="font-semibold text-blue-700">{{ ucfirst($role->name) }}</span></p>
            </div>

            {{-- Card --}}
            <div class="bg-white rounded-xl shadow p-6">

                {{-- Permission Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-left">Permission</th>
                                <th class="p-3 text-center w-24">
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs text-gray-500 mb-1">Create</span>
                                        <span class="font-medium text-gray-600">C</span>
                                    </div>
                                </th>
                                <th class="p-3 text-center w-24">
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs text-gray-500 mb-1">Read</span>
                                        <span class="font-medium text-gray-600">R</span>
                                    </div>
                                </th>
                                <th class="p-3 text-center w-24">
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs text-gray-500 mb-1">Update</span>
                                        <span class="font-medium text-gray-600">U</span>
                                    </div>
                                </th>
                                <th class="p-3 text-center w-24">
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs text-gray-500 mb-1">Delete</span>
                                        <span class="font-medium text-gray-600">D</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allPermissions as $perm)
                                @php
                                    $hasPerm = $role->permissions->contains($perm->id);
                                    $crud = $permissionCrud[$perm->id] ?? ['create' => false, 'read' => false, 'update' => false, 'delete' => false];
                                @endphp
                                <tr class="border-t">
                                    <td class="p-3 text-gray-700">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox"
                                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 perm-main"
                                                {{ $hasPerm ? 'checked' : '' }}
                                                disabled>
                                            <span class="font-medium">{{ ucfirst($perm->name) }}</span>
                                        </label>
                                    </td>
                                    @foreach(['create', 'read', 'update', 'delete'] as $action)
                                        <td class="p-3 text-center">
                                            <input type="checkbox"
                                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                {{ $crud[$action] ? 'checked' : '' }}
                                                disabled>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('admin.management_role.index') }}"
                       class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-semibold transition">
                        Batal
                    </a>
                    <button type="button"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition">
                        Simpan
                    </button>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
