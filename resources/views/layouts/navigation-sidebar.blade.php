{{-- Link untuk SEMUA user yang login --}}
<x-side-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
    {{ __('Dashboard') }}
</x-side-nav-link>

<x-side-nav-link :href="route('inspections.index')" :active="request()->routeIs('inspections.index') || request()->routeIs('inspections.show')">
    {{ __('Riwayat Inspeksi') }}
</x-side-nav-link>

<x-side-nav-link :href="route('inspections.create')" :active="request()->routeIs('inspections.create')">
    {{ __('Mulai Inspeksi') }}
</x-side-nav-link>

{{-- Link KHUSUS untuk Admin --}}
@if(auth()->user()->role === 'admin')
    <p class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Manajemen</p>
    
    <x-side-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
        {{ __('Produk') }}
    </x-side-nav-link>

    <x-side-nav-link :href="route('admin.checklist-templates.create')" :active="request()->routeIs('admin.checklist-templates.*')">
        {{ __('Template') }}
    </x-side-nav-link>
@endif
