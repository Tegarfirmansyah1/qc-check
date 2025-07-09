@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manajemen Template') }}
    </h2>
@endsection

@push('styles')
<style>
    @media (max-width: 767px) {
        .responsive-table thead { display: none; }
        .responsive-table tr { display: block; margin-bottom: 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1); background-color: #ffffff; }
        .responsive-table td { display: block; text-align: right; padding-left: 50%; position: relative; border-bottom: 1px solid #edf2f7; }
        .responsive-table td:last-child { border-bottom: none; padding-top: 1rem; padding-bottom: 1rem; }
        .responsive-table td::before { content: attr(data-label); position: absolute; left: 0.75rem; width: 45%; padding-right: 0.75rem; font-weight: 600; text-align: left; color: #4a5568; }
    }
</style>
@endpush

@section('content')
<div class="py-6 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg rounded-xl">
            <div class="p-4 sm:p-6 text-gray-900">
                
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                
                <div class="mb-6">
                    <a href="{{ route('admin.checklist-templates.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        + Tambah Template Baru
                    </a>
                </div>

                <table class="min-w-full responsive-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Template</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Untuk Produk</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 md:divide-y-0">
                        @forelse ($checklistTemplates as $template)
                            <tr>
                                <td data-label="Judul" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $template->title }}</td>
                                <td data-label="Produk" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $template->product->name }}</td>
                                <td data-label="Aksi" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    {{-- 👇 PERBAIKI BAGIAN INI --}}
                                    <div class="flex flex-wrap justify-end items-center gap-x-4 gap-y-2 ms-10">
                                        <a href="{{ route('admin.checklist-templates.show', $template) }}" class="text-gray-600 hover:text-gray-900">Lihat Item</a>
                                        <a href="{{ route('admin.checklist-templates.edit', $template) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <form class="inline-block" action="{{ route('admin.checklist-templates.destroy', $template) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus template ini? Semua item di dalamnya juga akan terhapus.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                    Belum ada data template.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

