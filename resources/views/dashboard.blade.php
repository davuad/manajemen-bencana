<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @role('admin')
                        <x-dashboard.admin />
                    @elserole('relawan')
                        <x-dashboard.relawan />
                    @elserole('kadus')
                        <x-dashboard.kadus />
                    @elserole('kabid')
                        <x-dashboard.kabid />
                    @elserole('desa')
                        <x-dashboard.desa />
                    @elserole('ketua_tim')
                        <x-dashboard.ketua_tim />
                    @else
                        <p class="text-red-500">Role tidak dikenali atau belum diatur.</p>
                    @endrole

                </div>
            </div>
        </div>
    </div>
</x-app-layout>